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
     *
     * @return T
     *
     * @throws EntityFactoryException Throws if entity is not set as argument, but can not be constructed.
     * @throws ReflectionException Throws if class reflection fails (unknown class etc.)
     */
    public function getInstance(EntityImporterDefinition $definition)
    {
        $entityClass           = $definition->getEntityClass();
        $entityClassReflection = new ReflectionClass($entityClass);
        $constructor           = $entityClassReflection->getConstructor();
        if (null !== $constructor && ($numRequiredParameters = $constructor->getNumberOfRequiredParameters()) > 0) {
            throw new EntityFactoryException(
                sprintf(
                    'Unable to create new entity using factory %s, constructor has %s required parameters.',
                    self::class,
                    $numRequiredParameters
                )
            );
        }

        $entity = $entityClassReflection->newInstance();

        unset($entityClassReflection, $constructor);

        return $entity;
    }
}
