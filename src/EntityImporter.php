<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter;

use Doctrine\Persistence\ObjectManager;
use Fastbolt\EntityImporter\Exceptions\InvalidInputFormatException;
use Fastbolt\EntityImporter\Factory\ArrayToEntityFactory;
use Fastbolt\EntityImporter\Reader\Factory\ReaderFactoryManager;
use Fastbolt\EntityImporter\Types\ImportError;
use Fastbolt\EntityImporter\Types\ImportResult;
use Throwable;

/**
 * @template T
 */
class EntityImporter
{
    /**
     * @var ReaderFactoryManager
     */
    private ReaderFactoryManager $readerFactoryManager;

    /**
     * @var ArrayToEntityFactory<T>
     */
    private ArrayToEntityFactory $defaultItemFactory;

    /**
     * @var ObjectManager
     */
    private ObjectManager $objectManager;

    /**
     * @param ReaderFactoryManager    $readerFactoryManager
     * @param ArrayToEntityFactory<T> $defaultItemFactory
     * @param ObjectManager           $objectManager
     */
    public function __construct(
        ReaderFactoryManager $readerFactoryManager,
        ArrayToEntityFactory $defaultItemFactory,
        ObjectManager $objectManager
    ) {
        $this->readerFactoryManager = $readerFactoryManager;
        $this->defaultItemFactory   = $defaultItemFactory;
        $this->objectManager        = $objectManager;
    }

    /**
     * @param EntityImporterDefinition<T> $definition
     * @param callable():void             $statusCallback
     * @param callable(Throwable):void    $errorCallback
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
        $addRows       = $sourceDefinition->skipFirstRow() ? 0 : 1;
        $flushInterval = $definition->getFlushInterval();

        $entityModifier = $definition->getEntityModifier();
        $readerFactory  = $this->readerFactoryManager->getReaderFactory($sourceDefinition->getType());
        $reader         = $readerFactory->getReader($definition, $sourceDefinition->getOptions());
        if (count($errors = $reader->getErrors()) > 0) {
            throw new InvalidInputFormatException($sourceDefinition->getSource(), $errors);
        }

        /**
         * @var array<string,mixed> $row We expect this to always be assoc, since we set the columnHeaders property before.
         * @var int                 $index
         */
        foreach ($reader as $index => $row) {
            if (0 === $index && $sourceDefinition->skipFirstRow()) {
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
                if (null !== $entityModifier) {
                    $entityModifier($item, $row);
                }

                $this->objectManager->persist($item);

                $statusCallback();
                $result->increaseSuccess();
            } catch (Throwable $exception) {
                $error = new ImportError($index, $exception->getMessage());

                $errorCallback($error);
                $result->addError($error);
            }
        }
        $this->objectManager->flush();
        $archivingResult = $sourceDefinition->getArchivingStrategy()
                                            ->archive($sourceDefinition);
        $result->setArchivingResult($archivingResult);

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
        $columns    = $definition->getIdentifierColumns();
        $dataFilter = array_filter(
            $row,
            static function (string $key) use ($columns) {
                return in_array($key, $columns, true);
            },
            ARRAY_FILTER_USE_KEY
        );
        $mappings   = $definition->getFieldNameMapping();
        $converters = $definition->getFieldConverters();
        $criteria   = [];
        foreach ($dataFilter as $sourceFieldName => $value) {
            if (null !== ($converter = $converters[$sourceFieldName] ?? null)) {
                $value = $converter($value);
            }

            $targetFieldName = $mappings[$sourceFieldName] ?? $sourceFieldName;

            $criteria[$targetFieldName] = $value;
        }

        if (null !== ($criteriaModifier = $definition->getIdentifierModifier())) {
            $criteria = $criteriaModifier($criteria);
        }

        return $criteria;
    }
}
