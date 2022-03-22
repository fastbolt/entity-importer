<?php

namespace Fastbolt\EntityImporter;

/**
 * @template   T
 * @implements EntityImporterDefinition<T>
 */
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
     * @return array<string,callable(null|object, array<string,mixed>):mixed>
     */
    public function getFieldConverters(): array
    {
        return [];
    }

    /**
     * @return callable(EntityImporterDefinition, null|T, array<string, mixed>):T|null
     */
    public function getEntityFactory(): ?callable
    {
        return null;
    }
}
