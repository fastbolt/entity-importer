<?php

namespace Fastbolt\EntityImporter\Factory;

use Fastbolt\EntityImporter\EntityImporterDefinition;

/**
 * @template T
 */
class ArrayToEntityFactory
{
    /**
     * @var EntityInstantiator
     */
    private EntityInstantiator $entityInstantiator;

    /**
     * @var EntityUpdater
     */
    private EntityUpdater $entityUpdater;

    /**
     * @param EntityInstantiator $entityInstantiator
     * @param EntityUpdater      $entityUpdater
     */
    public function __construct(EntityInstantiator $entityInstantiator, EntityUpdater $entityUpdater)
    {
        $this->entityInstantiator = $entityInstantiator;
        $this->entityUpdater      = $entityUpdater;
    }

    /**
     * @param EntityImporterDefinition<T> $definition
     * @param T|null                      $entity
     * @param array<string,mixed>         $row
     *
     * @return T
     */
    public function __invoke(EntityImporterDefinition $definition, $entity, array $row)
    {
        if (null === $entity) {
            $entity = null !== ($entityInstantiator = $definition->getEntityInstantiator())
                ? $entityInstantiator()
                : $this->entityInstantiator->getInstance($definition);
        }

        return $this->entityUpdater->setData($definition, $entity, $row);
    }
}
