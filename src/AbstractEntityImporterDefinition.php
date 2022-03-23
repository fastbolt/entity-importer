<?php

namespace Fastbolt\EntityImporter;

/**
 * @template   T
 * @implements EntityImporterDefinition<T>
 */
abstract class AbstractEntityImporterDefinition implements EntityImporterDefinition
{
    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function getFieldConverters(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getEntityFactory(): ?callable
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getFlushInterval(): int
    {
        return 1000;
    }

    /**
     * @inheritDoc
     */
    public function getSkippedFields(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getEntityInstantiator(): ?callable
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getEntityModifier(): ?callable
    {
        return null;
    }
}
