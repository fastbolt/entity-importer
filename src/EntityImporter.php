<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter;

use Doctrine\Persistence\ObjectManager;
use Exception;
use Fastbolt\EntityImporter\Exceptions\ImportFileNotFoundException;
use Fastbolt\EntityImporter\Factory\ArrayToEntityFactory;
use Fastbolt\EntityImporter\Filesystem\ArchivingStrategy;
use Fastbolt\EntityImporter\Reader\ReaderFactory;
use Fastbolt\EntityImporter\Types\ImportError;
use Fastbolt\EntityImporter\Types\ImportResult;

/**
 * @template T
 */
class EntityImporter
{
    /**
     * @var ReaderFactory
     */
    private ReaderFactory $readerFactory;

    /**
     * @var ArrayToEntityFactory<T>
     */
    private ArrayToEntityFactory $defaultItemFactory;

    /**
     * @var ObjectManager
     */
    private ObjectManager $objectManager;

    /**
     * @var ArchivingStrategy
     */
    private ArchivingStrategy $archivingStrategy;

    /**
     * @var string
     */
    private string $importPath;

    /**
     * @param ReaderFactory           $readerFactory
     * @param ArrayToEntityFactory<T> $defaultItemFactory
     * @param ObjectManager           $objectManager
     * @param ArchivingStrategy       $archivingStrategy
     * @param string                  $importPath
     */
    public function __construct(
        ReaderFactory $readerFactory,
        ArrayToEntityFactory $defaultItemFactory,
        ObjectManager $objectManager,
        ArchivingStrategy $archivingStrategy,
        string $importPath
    ) {
        $this->readerFactory      = $readerFactory;
        $this->defaultItemFactory = $defaultItemFactory;
        $this->objectManager      = $objectManager;
        $this->archivingStrategy  = $archivingStrategy;
        $this->importPath         = $importPath;
    }

    /**
     * @param EntityImporterDefinition<T> $definition
     * @param callable():void             $statusCallback
     * @param callable(Exception):void    $errorCallback
     * @param int|null                    $limit
     *
     * @return ImportResult
     */
    public function import(
        EntityImporterDefinition $definition,
        callable $statusCallback,
        callable $errorCallback,
        ?int $limit
    ): ImportResult {
        $result           = new ImportResult();
        $sourceDefinition = $definition->getImportSourceDefinition();
        $repository       = $definition->getRepository();
        $factoryCallback  = $this->defaultItemFactory;

        if (null !== ($customFactoryCallback = $definition->getEntityFactory())) {
            $factoryCallback = $customFactoryCallback;
        }
        $addRows        = $sourceDefinition->hasHeaderRow() ? 0 : 1;
        $flushInterval  = $definition->getFlushInterval();
        $importFilePath = sprintf(
            '%s/%s',
            $sourceDefinition->getImportDir() ?? $this->importPath,
            $sourceDefinition->getFilename()
        );
        if (!file_exists($importFilePath)) {
            throw new ImportFileNotFoundException($importFilePath);
        }

        $reader = $this->readerFactory->getReader($sourceDefinition, $importFilePath);
        $reader->setColumnHeaders($definition->getFields());

        /**
         * @var array<string,mixed> $row We expect this to always be assoc, since we set the columnHeaders property before.
         */
        foreach ($reader as $index => $row) {
            if (0 === $index && $sourceDefinition->hasHeaderRow()) {
                continue;
            }

            if (null !== $limit && $index + $addRows > $limit) {
                break;
            }

            if ($index > 0 && $index % $flushInterval === 0) {
                $this->objectManager->flush();
            }

            try {
                $item = $repository->findOneBy($this->getRepositorySelectionArray($definition, $row));
                $item = $factoryCallback($definition, $item, $row);
                if (null !== ($entityModifier = $definition->getEntityModifier())) {
                    $entityModifier($item);
                }

                $this->objectManager->persist($item);

                $statusCallback();
                $result->increaseSuccess();
            } catch (Exception $exception) {
                $error = new ImportError($index, $exception->getMessage());

                $errorCallback($error);
                $result->addError($error);
            }
        }
        $this->objectManager->flush();
        $archivedFilePath = $this->archivingStrategy->archiveFile($importFilePath);
        $result->setArchivedFilePath($archivedFilePath);

        return $result;
    }

    /**
     * @param EntityImporterDefinition<T> $definition
     * @param array<string,mixed>         $row
     *
     * @return array<string,mixed>
     */
    private function getRepositorySelectionArray(EntityImporterDefinition $definition, array $row): array
    {
        $columns = $definition->getIdentifierColumns();

        return array_filter(
            $row,
            static function (string $key) use ($columns) {
                return in_array($key, $columns, true);
            },
            ARRAY_FILTER_USE_KEY
        );
    }
}
