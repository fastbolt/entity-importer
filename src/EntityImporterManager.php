<?php

namespace Fastbolt\EntityImporter;

class EntityImporterManager
{
    /**
     * @var array<string,EntityImporterDefinition>
     */
    private array $definitions = [];

    private EntityImporter $importer;

    /**
     * @param EntityImporter                     $importer
     * @param iterable<EntityImporterDefinition> $importerDefinitions
     */
    public function __construct(EntityImporter $importer, iterable $importerDefinitions)
    {
        $this->importer = $importer;

        foreach ($importerDefinitions as $importerDefinition) {
            $this->definitions[$importerDefinition->getName()] = $importerDefinition;
        }
    }

    /**
     * @return array<string,EntityImporterDefinition>
     */
    public function getImporterDefinitions(): array
    {
        return $this->definitions;
    }

    public function import(string $name, callable $statusCallback, callable $errorCallback): ImportResult
    {
        if (!$name || null === ($definition = $this->definitions[$name] ?? null)) {
            throw new ImporterDefinitionNotFoundException(
                sprintf(
                    'Invalid definition: %s',
                    $name
                )
            );
        }

        return $this->importer->import($definition, $statusCallback, $errorCallback);
    }
}
