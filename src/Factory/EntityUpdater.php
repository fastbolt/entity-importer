<?php

namespace Fastbolt\EntityImporter\Factory;

use Fastbolt\EntityImporter\EntityImporterDefinition;
use Fastbolt\EntityImporter\Exceptions\SetterDetectionException;

/**
 * @template T
 */
class EntityUpdater
{

    /**
     * @var SetterDetector[]
     */
    private iterable $setterDetectors;

    /**
     * @param iterable<SetterDetector> $setterDetectors
     */
    public function __construct(iterable $setterDetectors)
    {
        $detectors = [];
        foreach ($setterDetectors as $detector) {
            $detectors[$detector->getPriority()] = $detector;
        }
        ksort($detectors);
        $this->setterDetectors = $detectors;
    }

    /**
     * @param EntityImporterDefinition<T> $definition
     * @param T                           $entity
     * @param array<string,mixed>         $row
     *
     * @return T
     */
    public function setData(EntityImporterDefinition $definition, $entity, array $row)
    {
        $converters = $definition->getFieldConverters();
        foreach ($row as $key => $value) {
            if (null !== ($converter = $converters[$key] ?? null)) {
                $value = $converter($value);
            }
            $setter = $this->detectSetter($definition, $entity, $key, $value);

            $entity->$setter($value);
        }

        return $entity;
    }

    /**
     * @param EntityImporterDefinition<T> $definition
     * @param T                           $entity
     * @param string                      $key
     * @param mixed                       $value
     *
     * @return string|null
     */
    private function detectSetter(EntityImporterDefinition $definition, $entity, string $key, $value): ?string
    {
        foreach ($this->setterDetectors as $detector) {
            if (null !== ($setter = $detector->detectSetter($entity, $key, $value))) {
                return $setter;
            }
        }

        throw new SetterDetectionException($definition, $key, $value);
    }
}
