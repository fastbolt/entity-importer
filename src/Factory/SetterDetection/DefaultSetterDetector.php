<?php

namespace Fastbolt\EntityImporter\Factory\SetterDetection;

use Fastbolt\EntityImporter\Factory\SetterDetector;
use Symfony\Component\String\UnicodeString;

/**
 * @template T
 */
class DefaultSetterDetector implements SetterDetector
{
    /**
     * @param T      $entity
     * @param string $key
     * @param mixed  $value
     *
     * @return string|null
     */
    public function detectSetter($entity, string $key, $value): ?string
    {
        $stringObject = new UnicodeString($key);
        $setterName   = sprintf(
            'set%s',
            $stringObject->camel()->title()
        );
        if (method_exists($entity, $setterName)) {
            return $setterName;
        }

        return null;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return 1000;
    }
}
