<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Tests\Unit\Factory;

use Fastbolt\EntityImporter\EntityImporterDefinition;
use Fastbolt\EntityImporter\Exceptions\EntityInstantiationException;
use Fastbolt\EntityImporter\Factory\EntityInstantiator;
use Fastbolt\EntityImporter\Tests\Unit\_Fixtures\Factory\EntityInstantiator\TestEntityNoConstructor;
use Fastbolt\EntityImporter\Tests\Unit\_Fixtures\Factory\EntityInstantiator\TestEntityWithConstructorNoArguments;
use Fastbolt\EntityImporter\Tests\Unit\_Fixtures\Factory\EntityInstantiator\TestEntityWithConstructorWithArgumentsNotMandatory;
use Fastbolt\EntityImporter\Tests\Unit\_Fixtures\Factory\EntityInstantiator\TestEntityWithConstructorWithMandatoryArguments;
use Fastbolt\TestHelpers\BaseTestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Fastbolt\EntityImporter\Factory\EntityInstantiator
 */
class EntityInstantiatorTest extends BaseTestCase
{
    /**
     * @var EntityImporterDefinition&MockObject
     */
    private $definition;

    public function testNoConstructor()
    {
        $instantiator = new EntityInstantiator();
        $this->definition->method('getEntityClass')
                         ->willReturn(TestEntityNoConstructor::class);

        $result = $instantiator->getInstance($this->definition);

        self::assertInstanceOf(TestEntityNoConstructor::class, $result);
    }

    public function testWithConstructorNoArguments()
    {
        $instantiator = new EntityInstantiator();
        $this->definition->method('getEntityClass')
                         ->willReturn(TestEntityWithConstructorNoArguments::class);

        $result = $instantiator->getInstance($this->definition);

        self::assertInstanceOf(TestEntityWithConstructorNoArguments::class, $result);
    }

    public function testWithConstructorWithArgumentsNotMandatory()
    {
        $instantiator = new EntityInstantiator();
        $this->definition->method('getEntityClass')
                         ->willReturn(TestEntityWithConstructorWithArgumentsNotMandatory::class);

        $result = $instantiator->getInstance($this->definition);

        self::assertInstanceOf(TestEntityWithConstructorWithArgumentsNotMandatory::class, $result);
    }

    public function testWithConstructorWithMandatoryArguments()
    {
        $this->expectException(EntityInstantiationException::class);

        $instantiator = new EntityInstantiator();
        $this->definition->method('getEntityClass')
                         ->willReturn(TestEntityWithConstructorWithMandatoryArguments::class);

        $instantiator->getInstance($this->definition);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->definition = $this->getMock(EntityImporterDefinition::class);
    }
}
