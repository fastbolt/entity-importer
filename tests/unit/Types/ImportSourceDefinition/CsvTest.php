<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Tests\Unit\Types\ImportSourceDefinition;

use Fastbolt\EntityImporter\ArchivingStrategy\ArchivingStrategy;
use Fastbolt\EntityImporter\Types\ImportSourceDefinition\Csv;
use Fastbolt\TestHelpers\BaseTestCase;

/**
 * @covers \Fastbolt\EntityImporter\Types\ImportSourceDefinition\Csv
 */
class CsvTest extends BaseTestCase
{
    private $archivingStrategy;

    public function testDefaults(): void
    {
        $definition = new Csv(
            'foo',
            $this->archivingStrategy
        );

        self::assertSame('foo', $definition->getSource());
        self::assertSame(';', $definition->getDelimiter());
        self::assertSame('"', $definition->getEnclosure());
        self::assertSame('\\', $definition->getEscape());
        self::assertTrue($definition->skipFirstRow());
        self::assertSame('csv', $definition->getType());
        self::assertSame([], $definition->getOptions());
        self::assertSame($this->archivingStrategy, $definition->getArchivingStrategy());
        self::assertTrue($definition->throwOnSourceUnavailable());

        $definition->setThrowOnSourceUnavailable(false);
        self::assertFalse($definition->throwOnSourceUnavailable());
    }

    public function testCustomValues(): void
    {
        $definition = new Csv(
            'foo',
            $this->archivingStrategy,
            'a',
            'b',
            'c',
            false
        );
        self::assertSame('foo', $definition->getSource());
        self::assertSame('a', $definition->getDelimiter());
        self::assertSame('b', $definition->getEnclosure());
        self::assertSame('c', $definition->getEscape());
        self::assertFalse($definition->skipFirstRow());
        self::assertSame('csv', $definition->getType());
        self::assertSame([], $definition->getOptions());
        self::assertSame($this->archivingStrategy, $definition->getArchivingStrategy());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->archivingStrategy = $this->getMock(ArchivingStrategy::class);
    }
}
