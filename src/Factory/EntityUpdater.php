<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Factory;

use Fastbolt\EntityImporter\EntityImporterDefinition;
use Fastbolt\EntityImporter\Exceptions\SetterDetectionException;

/**
 * @template T
 */
class EntityUpdater
{
    /**
     * @var array<string,array<string,string|null>>
     */
    private static $setterCache = [];

    /**
     * @var SetterDetector<T>[]
     */
    private iterable $setterDetectors;

    /**
     * @param iterable<SetterDetector<T>> $setterDetectors
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
     *
     * @throws SetterDetectionException Throws if no detector is able to detect setter.
     */
    public function setData(EntityImporterDefinition $definition, $entity, array $row)
    {
        $converters    = $definition->getFieldConverters();
        $skippedFields = $definition->getSkippedFields();
        foreach ($row as $key => $value) {
            if (in_array($key, $skippedFields, true)) {
                continue;
            }
            if (null !== ($converter = $converters[$key] ?? null)) {
                $value = $converter($value);
            }
            $setter = $this->detectSetter($definition, $entity, $key, $value);

            $entity->$setter($value);
        }

        return $entity;
    }

    /**
     * Detect setter for given field. Will cache results for performance reasons.
     *
     * @param EntityImporterDefinition<T> $definition
     * @param T                           $entity
     * @param string                      $key
     * @param mixed                       $value
     *
     * @return string|null
     *
     * @throws SetterDetectionException Throws if no detector is able to detect setter.
     */
    private function detectSetter(EntityImporterDefinition $definition, $entity, string $key, $value): ?string
    {
        $entityClass = get_class($entity);
        if (null !== ($setter = self::$setterCache[$entityClass][$key] ?? null)) {
            return $setter ?: null;
        }

        if (!isset(self::$setterCache[$entityClass])) {
            self::$setterCache[$entityClass] = [];
        }

        foreach ($this->setterDetectors as $detector) {
            if (null !== ($setter = $detector->detectSetter($entity, $key, $value))) {
                self::$setterCache[$entityClass][$key] = $setter ?: '';

                return $setter;
            }
        }

        throw new SetterDetectionException($definition, $key, $value);
    }
}
