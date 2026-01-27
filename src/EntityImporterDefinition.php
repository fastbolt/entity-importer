<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter;

use Doctrine\Persistence\ObjectRepository;
use Fastbolt\EntityImporter\Types\ImportSourceDefinition\ImportSourceDefinition;

/**
 * @template T
 */
interface EntityImporterDefinition
{
    /**
     * Name displayed in console / used to execute command.
     * Should not contain white spaces and special characters.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Description displayed in "Available import types" overview.
     *
     * @return string
     */
    public function getDescription(): string;

    /**
     * Full-qualified class-name of the entity to import.
     *
     * @return class-string
     */
    public function getEntityClass(): string;

    /**
     * Factory method to create an entity instance. Must return null or callable.
     * The callable will receive the importer-definition, the current entity-instance
     * (if any) and the current import source row array as arguments.
     * Must always return an entity instance.
     *
     * @return callable(EntityImporterDefinition<T>,T|null,array<string,mixed>):T|null
     */
    public function getEntityFactory(): ?callable;

    /**
     * Get list of fields to import. Must always contain all fields available in input source.
     *
     * @return array<int,string>
     */
    public function getFields(): array;

    /**
     * Get list of converters (callables) per field. Callable will recieve the current field value and must
     * return the correct value / type for the field.
     *
     * @return array<string, callable>
     */
    public function getFieldConverters(): array;

    /**
     * List of columns used as doctrine identifier, for loading existing items from database.
     *
     * @return array<int,string>
     */
    public function getIdentifierColumns(): array;

    /**
     * Get modifier callable applied to identifier selection before loading entity from database.
     * Can be used to add static columns to identifier selection or even modify selection array.
     *
     * @return callable(array<string,mixed>):array<string,mixed>|null
     */
    public function getIdentifierModifier(): ?callable;

    /**
     * Import source definition to specify source data format.
     *
     * @return ImportSourceDefinition
     */
    public function getImportSourceDefinition(): ImportSourceDefinition;

    /**
     * Repository instance to be used.
     *
     * @return ObjectRepository<T>
     */
    public function getRepository(): ObjectRepository;

    /**
     * Interval - every n'th row will flush the entity manager.
     *
     * @return int
     */
    public function getFlushInterval(): int;

    /**
     * Return list of skipped field names.
     *
     * @return array<int,string>
     */
    public function getSkippedFields(): array;

    /**
     * Callable used for entity creation.
     * Defaults to {@see \Fastbolt\EntityImporter\Factory\EntityInstantiator::getInstance()}.
     *
     * @return callable(array<string,mixed>):T|null
     */
    public function getEntityInstantiator(): ?callable;

    /**
     * Callable used to modify entity before persisting.
     *
     * @return callable(T, array<mixed>)|null
     */
    public function getEntityModifier(): ?callable;

    /**
     * Return list of mappings for differing field names between source and target.
     *
     * Example:
     *      ['name1' => 'lastname']
     *
     * @return array<string, string>
     */
    public function getFieldNameMapping(): array;

    /**
     * Option to disable throwing an exception on unknown fields.
     *
     * @return bool
     */
    public function isThrowExceptionOnUnknownField(): bool;
}
