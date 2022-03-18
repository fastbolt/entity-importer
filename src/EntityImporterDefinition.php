<?php

namespace Fastbolt\EntityImporter;

use Doctrine\Persistence\ObjectRepository;
use Fastbolt\EntityImporter\Types\ImportSourceDefinition;

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
     */
    public function getEntityClass(): string;

    /**
     * @return callable|null
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
     * @return ObjectRepository
     */
    public function getRepository(): ObjectRepository;
}
