<?php

namespace Fastbolt\EntityImporter;

abstract class AbstractEntityImporterDefinition implements EntityImporterDefinition
{
    public function getName(): string
    {
        return '';
    }

    public function getFieldConverters(): array
    {
        return [];
    }

    public function getEntityFactory(): ?callable
    {
        return null;
    }
}
