<?php

namespace Fastbolt\EntityImporter\Tests\Unit;

use Fastbolt\EntityImporter\AbstractEntityImporterDefinition;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Fastbolt\EntityImporter\AbstractEntityImporterDefinition
 */
class AbstractEntityImporterDefinitionTest extends TestCase
{
    private $definition;

    public function testDefaults()
    {
        self::assertSame('', $this->definition->getName());
        self::assertSame([], $this->definition->getFieldConverters());
        self::assertNull($this->definition->getEntityFactory());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->definition = $this->getMockForAbstractClass(AbstractEntityImporterDefinition::class);
    }
}
