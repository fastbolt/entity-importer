<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Tests\Unit\Factory\SetterDetection;

use Fastbolt\EntityImporter\EntityImporterDefinition;
use Fastbolt\EntityImporter\Factory\SetterDetection\MappedSetterDetector;
use Fastbolt\TestHelpers\BaseTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use stdClass;

/**
 * @covers \Fastbolt\EntityImporter\Factory\SetterDetection\MappedSetterDetector
 */
class MappedSetterDetectorTest extends BaseTestCase
{
    /**
     * @var EntityImporterDefinition&MockObject
     */
    private $definition;

    public function testDetector()
    {
        $detector = new MappedSetterDetector();
        self::assertSame(2000, $detector->getPriority());
        $this->definition->method('getSetterMapping')
                         ->willReturn(['foo' => 'setOne', 'bar' => 'setTwo']);

        $setter = $detector->detectSetter($this->definition, new stdClass(), 'foo', 'value');
        self::assertSame('setOne', $setter);

        $setter = $detector->detectSetter($this->definition, new stdClass(), 'bar', 'value');
        self::assertSame('setTwo', $setter);

        $setter = $detector->detectSetter($this->definition, new stdClass(), 'asd', 'value');
        self::assertNull($setter);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->definition = $this->getMock(EntityImporterDefinition::class);
    }
}
