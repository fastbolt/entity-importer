<?php

namespace Fastbolt\EntityImporter;

use Exception;
use Fastbolt\EntityImporter\Factory\ArrayToEntityFactory;
use Fastbolt\EntityImporter\Reader\ReaderFactory;
use Fastbolt\EntityImporter\Types\ImportError;
use Fastbolt\EntityImporter\Types\ImportResult;

class EntityImporter
{
    private ReaderFactory $readerFactory;

    private ArrayToEntityFactory $defaultItemFactory;

    public function __construct(ReaderFactory $readerFactory, ArrayToEntityFactory $defaultItemFactory)
    {
        $this->readerFactory      = $readerFactory;
        $this->defaultItemFactory = $defaultItemFactory;
    }

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
        $reader = $this->readerFactory->getReader($sourceDefinition);
        $reader->setColumnHeaders($definition->getFields());

        foreach ($reader as $index => $row) {
            if (0 === $index && $sourceDefinition->hasHeaderRow()) {
                continue;
            }

            if (null === $row) {
                continue;
            }

            try {
                $item = $repository->findOneBy($this->getRepositorySelectionArray($definition, $row));
                $factoryCallback($item, $row);
                $statusCallback();
                $result->increaseSuccess();
            } catch (Exception $exception) {
                $errorCallback($exception);
                $result->addError(new ImportError($index, $exception->getMessage()));
            }
        }

        return $result;
    }

    /**
     * @param EntityImporterDefinition $definition
     * @param array                    $row
     *
     * @return array<string,mixed>
     */
    private function getRepositorySelectionArray(EntityImporterDefinition $definition, array $row): array
    {
        $columns = $definition->getIdentifierColumns();

        return array_filter(
            $row,
            static function ($value, $key) use ($columns) {
                return in_array($key, $columns, true);
            },
            ARRAY_FILTER_USE_BOTH
        );
    }
}
