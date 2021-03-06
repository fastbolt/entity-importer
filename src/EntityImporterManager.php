<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter;

use Exception;
use Fastbolt\EntityImporter\Exceptions\ImporterDefinitionNotFoundException;
use Fastbolt\EntityImporter\Types\ImportResult;

class EntityImporterManager
{
    /**
     * @var array<string,EntityImporterDefinition>
     */
    private array $definitions = [];

    /**
     * @var EntityImporter
     */
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

    /**
     * @param string                   $name
     * @param callable():void          $statusCallback
     * @param callable(Exception):void $errorCallback
     * @param int|null                 $limit
     *
     * @return ImportResult
     */
    public function import(string $name, callable $statusCallback, callable $errorCallback, ?int $limit): ImportResult
    {
        if (!$name || null === ($definition = $this->definitions[$name] ?? null)) {
            throw new ImporterDefinitionNotFoundException($name);
        }

        return $this->importer->import($definition, $statusCallback, $errorCallback, $limit);
    }
}
