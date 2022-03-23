<?php

namespace Fastbolt\EntityImporter;

use Doctrine\Persistence\ObjectManager;
use Exception;
use Fastbolt\EntityImporter\Factory\ArrayToEntityFactory;
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
     * @param ReaderFactory           $readerFactory
     * @param ArrayToEntityFactory<T> $defaultItemFactory
     * @param ObjectManager           $objectManager
     */
    public function __construct(
        ReaderFactory $readerFactory,
        ArrayToEntityFactory $defaultItemFactory,
        ObjectManager $objectManager
    ) {
        $this->readerFactory      = $readerFactory;
        $this->defaultItemFactory = $defaultItemFactory;
        $this->objectManager      = $objectManager;
    }

    /**
     * @param EntityImporterDefinition<T> $definition
     * @param callable():void             $statusCallback
     * @param callable(Exception):void    $errorCallback
     *
     * @return ImportResult
     */
    public function import(
        EntityImporterDefinition $definition,
        callable $statusCallback,
        callable $errorCallback
    ): ImportResult {
        $result           = new ImportResult();
        $sourceDefinition = $definition->getImportSourceDefinition();
        $repository       = $definition->getRepository();
        $factoryCallback  = $this->defaultItemFactory;

        if (null !== ($customFactoryCallback = $definition->getEntityFactory())) {
            $factoryCallback = $customFactoryCallback;
        }
        $flushInterval = $definition->getFlushInterval();
        $reader        = $this->readerFactory->getReader($sourceDefinition);
        $reader->setColumnHeaders($definition->getFields());

        /**
         * @var array<string,mixed> $row We expect this to always be assoc, since we set the columnHeaders property before.
         */
        foreach ($reader as $index => $row) {
            if (0 === $index && $sourceDefinition->hasHeaderRow()) {
                continue;
            }

            try {
                $item = $repository->findOneBy($this->getRepositorySelectionArray($definition, $row));
                $item = $factoryCallback($definition, $item, $row);

                $this->objectManager->persist($item);

                $statusCallback();
                $result->increaseSuccess();

                if ($index > 0 && $index % $flushInterval === 0) {
                    $this->objectManager->flush();
                }
            } catch (Exception $exception) {
                $error = new ImportError($index, $exception->getMessage());

                $errorCallback($error);
                $result->addError($error);
            }
        }
        $this->objectManager->flush();

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
