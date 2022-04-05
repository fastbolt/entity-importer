<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Tests\Unit\Factory;

use DateTime;
use Fastbolt\EntityImporter\EntityImporterDefinition;
use Fastbolt\EntityImporter\Exceptions\SetterDetectionException;
use Fastbolt\EntityImporter\Factory\EntityUpdater;
use Fastbolt\EntityImporter\Factory\SetterDetection\DefaultSetterDetector;
use Fastbolt\EntityImporter\Factory\SetterDetector;
use Fastbolt\TestHelpers\BaseTestCase;
use Fastbolt\TestHelpers\Visibility;
use PHPUnit\Framework\MockObject\MockObject;
use stdClass;

/**
 * @covers \Fastbolt\EntityImporter\Factory\EntityUpdater
 */
class EntityUpdaterTest extends BaseTestCase
{
    /**
     * @var SetterDetector&MockObject
     */
    private $setterDetector1;

    /**
     * @var SetterDetector&MockObject
     */
    private $setterDetector2;

    /**
     * @var EntityImporterDefinition&MockObject
     */
    private $definition;

    /**
     * @var stdClass&MockObject
     */
    private $entity;

    public function testSortDetectors(): void
    {
        $this->setterDetector1->method('getPriority')
                              ->willReturn(1000);
        $this->setterDetector2->method('getPriority')
                              ->willReturn(500);
        $updater = new EntityUpdater([$this->setterDetector1, $this->setterDetector2]);
        self::assertSame(
            [500 => $this->setterDetector2, 1000 => $this->setterDetector1],
            Visibility::getProperty($updater, 'setterDetectors')
        );
    }

    public function testSetData(): void
    {
        $importRow = [
            'foo' => '123',
            'bar' => new DateTime(),
            'asd' => 'foo',
        ];
        $this->setterDetector1->method('getPriority')
                              ->willReturn(0);
        $this->setterDetector1->expects(self::exactly(2))
                              ->method('detectSetter')
                              ->withConsecutive(
                                  [$this->entity, 'foo', '321'],
                                  [$this->entity, 'bar', $importRow['bar']],
                              )
                              ->willReturnOnConsecutiveCalls(
                                  'setFoo',
                                  'setBar'
                              );
        $this->entity->expects(self::once())
                     ->method('setFoo')
                     ->with('321');
        $this->entity->expects(self::once())
                     ->method('setBar')
                     ->with($importRow['bar']);
        $this->definition->method('getSkippedFields')
                         ->willReturn(['asd']);
        $this->definition->method('getFieldConverters')
                         ->willReturn([
                                          'foo' => static function (string $value, array $row) use ($importRow
                                          ): string {
                                              self::assertSame($importRow, $row);

                                              return strrev($value);
                                          },
                                      ]);

        $updater = new EntityUpdater([$this->setterDetector1]);
        $result  = $updater->setData($this->definition, $this->entity, $importRow);

        self::assertSame($result, $this->entity);
    }

    public function testSetDataException(): void
    {
        $this->expectException(SetterDetectionException::class);

        $this->setterDetector1->method('getPriority')
                              ->willReturn(0);

        $updater = new EntityUpdater([$this->setterDetector1]);
        $updater->setData($this->definition, $this->entity, ['foo' => '123']);
    }

    public function testSetDataEnsureCaching(): void
    {
        $array = [
            'foo' => '123',
            'bar' => new DateTime(),
            'asd' => 'foo',
        ];
        $this->setterDetector1->method('getPriority')
                              ->willReturn(0);
        $this->setterDetector1->expects(self::exactly(2))
                              ->method('detectSetter')
                              ->withConsecutive(
                                  [$this->entity, 'foo', $array['foo']],
                                  [$this->entity, 'bar', $array['bar']],
                              )
                              ->willReturnOnConsecutiveCalls(
                                  'setFoo',
                                  'setBar'
                              );
        $this->entity->expects(self::atLeastOnce())
                     ->method('setFoo')
                     ->with($array['foo']);
        $this->entity->expects(self::atLeastOnce())
                     ->method('setBar')
                     ->with($array['bar']);
        $this->definition->method('getSkippedFields')
                         ->willReturn(['asd']);

        $updater = new EntityUpdater([$this->setterDetector1]);

        $updater->setData($this->definition, $this->entity, $array);
        $result = $updater->setData($this->definition, $this->entity, $array);
        self::assertSame($result, $this->entity);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->setterDetector1 = $this->getMock(DefaultSetterDetector::class);
        $this->setterDetector2 = $this->getMock(DefaultSetterDetector::class);
        $this->definition      = $this->getMock(EntityImporterDefinition::class);
        $this->entity          = $this->getMock(stdClass::class, [], ['setFoo', 'setBar']);
    }
}
