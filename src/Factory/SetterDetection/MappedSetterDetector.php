<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Factory\SetterDetection;

use Fastbolt\EntityImporter\EntityImporterDefinition;
use Fastbolt\EntityImporter\Factory\SetterDetector;

class MappedSetterDetector implements SetterDetector
{
    /**
     * @inheritDoc
     */
    public function detectSetter(EntityImporterDefinition $definition, object $entity, string $key, $value): ?string
    {
        if (null !== ($setter = $definition->getSetterMapping()[$key] ?? null)) {
            return $setter;
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function getPriority(): int
    {
        return 2000;
    }
}
