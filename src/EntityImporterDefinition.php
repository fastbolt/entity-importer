<?php

namespace Fastbolt\EntityImporter;

use Fastbolt\EntityImporter\Types\ImportSourceDefinition;

interface EntityImporterDefinition
{
    public function getName(): string;

    public function getDescription(): string;

    public function getEntityClass(): string;

    public function getEntityFactory(): ?callable;

    public function getFields(): array;

    public function getFieldConverters(): array;

    public function getIdentifierColumns(): array;

    public function getImportSourceDefinition(): ImportSourceDefinition;

    public function getRepository(): ObjectRepository;
}
