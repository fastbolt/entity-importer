<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Tests\Unit\Factory\SetterDetection;

use Fastbolt\EntityImporter\EntityImporterDefinition;
use Fastbolt\EntityImporter\Factory\SetterDetection\DefaultSetterDetector;
use Fastbolt\TestHelpers\BaseTestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Fastbolt\EntityImporter\Factory\SetterDetection\DefaultSetterDetector
 */
class DefaultSetterDetectorTest extends BaseTestCase
{
    /**
     * @var EntityImporterDefinition&MockObject
     */
    private $definition;

    /**
     * @dataProvider detectionDataProvider
     */
    public function testDetection(object $entity, string $key, ?string $expectedSetter, string $message): void
    {
        $detector = new DefaultSetterDetector();

        self::assertSame(1000, $detector->getPriority());
        self::assertSame($expectedSetter, $detector->detectSetter($this->definition, $entity, $key, ''), $message);
    }

    public function detectionDataProvider(): array
    {
        return [
            [
                new class {
                    private string $property;

                    public function setProperty(string $value): void
                    {
                        $this->property = $value;
                    }
                },
                'property',
                'setProperty',
                'Primitive property name',
            ],
            [
                new class {
                    private string $property;

                    public function setFooProperty(string $value): void
                    {
                        $this->property = $value;
                    }
                },
                'foo_property',
                'setFooProperty',
                'Snake case to camel case property name',
            ],
            [
                new class {
                },
                'foo_property',
                null,
                'Fail to detect missing setter',
            ],
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->definition = $this->getMock(EntityImporterDefinition::class);
    }
}
