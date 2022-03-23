<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
