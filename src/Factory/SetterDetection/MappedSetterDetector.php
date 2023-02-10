<?php

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
