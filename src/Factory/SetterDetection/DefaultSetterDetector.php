<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Factory\SetterDetection;

use Fastbolt\EntityImporter\Factory\SetterDetector;
use Symfony\Component\String\UnicodeString;

class DefaultSetterDetector implements SetterDetector
{
    /**
     * @inheritDoc
     */
    public function detectSetter(object $entity, string $key, $value): ?string
    {
        $stringObject = new UnicodeString($key);
        $setterName   = sprintf(
            'set%s',
            $stringObject->camel()->title()->toString()
        );
        if (method_exists($entity, $setterName)) {
            return $setterName;
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function getPriority(): int
    {
        return 1000;
    }
}
