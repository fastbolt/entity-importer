<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter;

use DateTime;
use Exception;
use Fastbolt\EntityImporter\Events\ImportFailureEvent;
use Fastbolt\EntityImporter\Events\ImportSuccessEvent;
use Fastbolt\EntityImporter\Exceptions\ImporterDefinitionNotFoundException;
use Fastbolt\EntityImporter\Exceptions\InvalidInputFormatException;
use Fastbolt\EntityImporter\Exceptions\SourceUnavailableException;
use Fastbolt\EntityImporter\Types\ImportResult;
use InvalidArgumentException;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Throwable;

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
     * @var EventDispatcherInterface
     */
    private EventDispatcherInterface $eventDispatcher;

    /**
     * @param EntityImporter                     $importer
     * @param EventDispatcherInterface           $eventDispatcher
     * @param iterable<EntityImporterDefinition> $importerDefinitions
     */
    public function __construct(
        EntityImporter $importer,
        EventDispatcherInterface $eventDispatcher,
        iterable $importerDefinitions
    ) {
        $this->importer        = $importer;
        $this->eventDispatcher = $eventDispatcher;

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
     * @param callable(Throwable):void $errorCallback
     * @param int|null                 $limit
     *
     * @return ImportResult
     *
     * @throws InvalidInputFormatException
     * @throws SourceUnavailableException
     * @throws InvalidArgumentException
     */
    public function import(string $name, callable $statusCallback, callable $errorCallback, ?int $limit): ImportResult
    {
        $start = new DateTime();
        try {
            if (!$name) {
                throw new InvalidArgumentException('Name must not be empty');
            }

            if (null === ($definition = $this->definitions[$name] ?? null)) {
                throw new ImporterDefinitionNotFoundException($name);
            }

            $return = $this->importer->import($definition, $statusCallback, $errorCallback, $limit);
        } catch (Exception $exception) {
            $this->eventDispatcher->dispatch(new ImportFailureEvent($definition, $start, $exception));

            throw $exception;
        }

        $this->eventDispatcher->dispatch(new ImportSuccessEvent($definition, $start, $return));

        return $return;
    }
}
