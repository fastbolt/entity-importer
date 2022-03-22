<?php

namespace Fastbolt\EntityImporter\Factory;

use Fastbolt\EntityImporter\EntityImporterDefinition;
use Fastbolt\EntityImporter\Exceptions\EntityFactoryException;
use ReflectionClass;
use ReflectionException;

/**
 * @template T
 */
class EntityInstantiator
{
    /**
     * @param EntityImporterDefinition<T> $definition
     * @param T|null                      $entity
     *
     * @return T
     *
     * @throws EntityFactoryException Throws if entity is not set as argument, but can not be constructed.
     * @throws ReflectionException Throws if class reflection fails (unknown class etc.)
     */
    public function getInstance(EntityImporterDefinition $definition, $entity)
    {
        $entityClass           = $definition->getEntityClass();
        $entityClassReflection = new ReflectionClass($entityClass);
        $constructor           = $entityClassReflection->getConstructor();
        if (
            null !== $constructor && null === $entity
            && ($numRequiredParameters = $constructor->getNumberOfRequiredParameters()) > 0
        ) {
            throw new EntityFactoryException(
                sprintf(
                    'Unable to create new entity using factory %s, constructor has %s required parameters.',
                    self::class,
                    $numRequiredParameters
                )
            );
        }

        if (null === $entity) {
            $entity = $entityClassReflection->newInstance();
        }

        unset($entityClassReflection, $constructor);

        return $entity;
    }
}
