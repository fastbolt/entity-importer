<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Tests\Unit\Factory;

use Fastbolt\EntityImporter\EntityImporterDefinition;
use Fastbolt\EntityImporter\Factory\ArrayToEntityFactory;
use Fastbolt\EntityImporter\Factory\EntityInstantiator;
use Fastbolt\EntityImporter\Factory\EntityUpdater;
use Fastbolt\TestHelpers\BaseTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use stdClass;

/**
 * @covers \Fastbolt\EntityImporter\Factory\ArrayToEntityFactory
 */
class ArrayToEntityFactoryTest extends BaseTestCase
{
    /**
     * @var EntityInstantiator&MockObject
     */
    private $entityInstantiator;

    /**
     * @var EntityUpdater&MockObject
     */
    private $entityUpdater;

    /**
     * @var callable&MockObject
     */
    private $customEntityInstantiator;

    /**
     * @var EntityImporterDefinition&MockObject
     */
    private $definition;

    public function testWithEntity(): void
    {
        $entity = new stdClass();
        $row    = ['foo' => 'asd', 'bar' => 'dsa'];
        $this->definition->method('getEntityInstantiator')
                         ->willReturn(null);
        $this->entityInstantiator->expects(self::never())
                                 ->method('getInstance');
        $this->customEntityInstantiator->expects(self::never())
                                       ->method('__invoke');
        $this->entityUpdater->expects(self::once())
                            ->method('setData')
                            ->with($this->definition, $entity, $row)
                            ->willReturn($entity);
        $factory = new ArrayToEntityFactory($this->entityInstantiator, $this->entityUpdater);
        $result  = $factory($this->definition, $entity, $row);

        self::assertSame($entity, $result);
    }

    public function testNullEntityWithDefaultEntityInstantiator(): void
    {
        $entity = new stdClass();
        $row    = ['foo' => 'asd', 'bar' => 'dsa'];
        $this->definition->method('getEntityInstantiator')
                         ->willReturn(null);
        $this->entityInstantiator->expects(self::once())
                                 ->method('getInstance')
                                 ->with($this->definition)
                                 ->willReturn($entity);
        $this->customEntityInstantiator->expects(self::never())
                                       ->method('__invoke');
        $this->entityUpdater->expects(self::once())
                            ->method('setData')
                            ->with($this->definition, $entity, $row)
                            ->willReturn($entity);
        $factory = new ArrayToEntityFactory($this->entityInstantiator, $this->entityUpdater);
        $result  = $factory($this->definition, null, $row);

        self::assertSame($entity, $result);
    }

    public function testNullEntityWithCustomEntityInstantiator(): void
    {
        $entity = new stdClass();
        $row    = ['foo' => 'asd', 'bar' => 'dsa'];
        $this->definition->method('getEntityInstantiator')
                         ->willReturn($this->customEntityInstantiator);
        $this->entityInstantiator->expects(self::never())
                                 ->method('getInstance')
                                 ->with($this->definition);
        $this->customEntityInstantiator->expects(self::once())
                                       ->method('__invoke')
                                       ->willReturn($entity);
        $this->entityUpdater->expects(self::once())
                            ->method('setData')
                            ->with($this->definition, $entity, $row)
                            ->willReturn($entity);
        $factory = new ArrayToEntityFactory($this->entityInstantiator, $this->entityUpdater);
        $result  = $factory($this->definition, null, $row);

        self::assertSame($entity, $result);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->entityInstantiator       = $this->getMock(EntityInstantiator::class);
        $this->entityUpdater            = $this->getMock(EntityUpdater::class);
        $this->customEntityInstantiator = $this->getCallable();
        $this->definition               = $this->getMock(EntityImporterDefinition::class);
    }
}
