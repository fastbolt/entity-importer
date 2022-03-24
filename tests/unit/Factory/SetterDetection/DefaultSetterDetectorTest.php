<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Tests\Unit\Factory\SetterDetection;

use Fastbolt\EntityImporter\Factory\SetterDetection\DefaultSetterDetector;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Fastbolt\EntityImporter\Factory\SetterDetection\DefaultSetterDetector
 */
class DefaultSetterDetectorTest extends TestCase
{
    /**
     * @dataProvider detectionDataProvider
     */
    public function testDetection(object $entity, string $key, ?string $expectedSetter, string $message)
    {
        $detector = new DefaultSetterDetector();

        self::assertSame(1000, $detector->getPriority());
        self::assertSame($expectedSetter, $detector->detectSetter($entity, $key, ''), $message);
    }

    public function detectionDataProvider()
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
}
