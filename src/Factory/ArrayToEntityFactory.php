<?php

namespace Fastbolt\EntityImporter\Factory;

/**
 * @template T
 */
class ArrayToEntityFactory
{
    /**
     * @param T|null              $entity
     * @param array<string,mixed> $row
     *
     * @return void
     */
    public function __invoke($entity, array $row): void
    {
    }
}
