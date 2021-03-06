<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Factory;

use Fastbolt\EntityImporter\EntityImporterDefinition;
use Fastbolt\EntityImporter\Exceptions\EntityInstantiationException;
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
     * @throws EntityInstantiationException Throws if entity is not set as argument, but can not be constructed.
     * @throws ReflectionException Throws if class reflection fails (unknown class etc.)
     */
    public function getInstance(EntityImporterDefinition $definition): object
    {
        $entityClass           = $definition->getEntityClass();
        $entityClassReflection = new ReflectionClass($entityClass);
        $constructor           = $entityClassReflection->getConstructor();
        if (null !== $constructor && ($numRequiredParameters = $constructor->getNumberOfRequiredParameters()) > 0) {
            throw new EntityInstantiationException(
                $entityClass,
                $numRequiredParameters
            );
        }

        /** @var T $entity */
        $entity = $entityClassReflection->newInstance();

        unset($entityClassReflection, $constructor);

        return $entity;
    }
}
