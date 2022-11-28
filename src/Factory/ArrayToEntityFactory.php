<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
     * @param-out T $entity
     */
    public function __invoke(EntityImporterDefinition $definition, ?object $entity, array $row): object
    {
        if (null === $entity) {
            if (null !== ($entityInstantiator = $definition->getEntityInstantiator())) {
                $entity = $entityInstantiator($row);
            } else {
                $entity = $this->entityInstantiator->getInstance($definition);
            }
        }

        return $this->entityUpdater->setData($definition, $entity, $row);
    }
}
