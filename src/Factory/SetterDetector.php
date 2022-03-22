<?php

namespace Fastbolt\EntityImporter\Factory;

/**
 * @template T
 */
interface SetterDetector
{
    /**
     * @param T      $entity
     * @param string $key
     * @param mixed  $value
     *
     * @return string|null
     */
    public function detectSetter($entity, string $key, $value): ?string;

    /**
     * @return int
     */
    public function getPriority(): int;
}
