<?php

namespace Fastbolt\EntityImporter;

abstract class AbstractEntityImporterDefinition implements EntityImporterDefinition
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return '';
    }

    /**
     * @return array<string,callable>
     */
    public function getFieldConverters(): array
    {
        return [];
    }

    /**
     * @return callable|null
     */
    public function getEntityFactory(): ?callable
    {
        return null;
    }
}
