<?php

namespace Fastbolt\EntityImporter;

use Doctrine\ORM\EntityManagerInterface;
use Fastbolt\EntityImporter\Reader\ReaderFactory;
use Fastbolt\EntityImporter\Types\ImportResult;

class EntityImporter
{
    private ReaderFactory $readerFactory;

    private EntityManagerInterface $entityManager;

    private $defaultItemFactory;

    /**
     * @param ReaderFactory          $readerFactory
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ReaderFactory $readerFactory, EntityManagerInterface $entityManager)
    {
        $this->readerFactory = $readerFactory;
    }

    public function import(EntityImporterDefinition $definition, callable $statusCallback, callable $errorCallback)
    {
        $result           = new ImportResult();
        $sourceDefinition = $definition->getImportSourceDefinition();
        $repository       = $definition->getRepository();
        if (null === ($factoryCallback = $definition->getEntityFactory())) {
            $factoryCallback = $this->defaultItemFactory;
        }
        $reader = $this->readerFactory->getReader($sourceDefinition);
        $reader->setColumnHeaders($definition->getFields());

        foreach ($reader as $index => $row) {
            if (0 === $index && $sourceDefinition->hasHeaderRow()) {
                continue;
            }

            $item = $repository->findOneBy($this->getRepositorySelectionArray($definition, $row));
            $factoryCallback($item, $row);
        }

        return $result;
    }

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
