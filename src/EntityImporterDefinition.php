<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter;

use Doctrine\Persistence\ObjectRepository;
use Fastbolt\EntityImporter\Types\ImportSourceDefinition;

/**
 * @template T
 */
interface EntityImporterDefinition
{
    /**
     * @return string
     */
    public function getName(): string;

    /***
     * @return string
     */
    public function getDescription(): string;

    /**
     * @return string
     * @psalm-return class-string T
     */
    public function getEntityClass(): string;

    /**
     * @return callable(EntityImporterDefinition,T|null,array<string,mixed>):T|null
     */
    public function getEntityFactory(): ?callable;

    /**
     * @return array<int,string>
     */
    public function getFields(): array;

    /**
     * @return array<string,callable>
     */
    public function getFieldConverters(): array;

    /**
     * @return array<int,string>
     */
    public function getIdentifierColumns(): array;

    /**
     * @return ImportSourceDefinition
     */
    public function getImportSourceDefinition(): ImportSourceDefinition;

    /**
     * @return ObjectRepository<T>
     */
    public function getRepository(): ObjectRepository;

    /**
     * @return int
     */
    public function getFlushInterval(): int;

    /**
     * @return array<int,string>
     */
    public function getSkippedFields(): array;

    /**
     * @return callable():T|null
     */
    public function getEntityInstantiator(): ?callable;

    /**
     * @return callable(T)|null
     */
    public function getEntityModifier(): ?callable;
}
