<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Tests\Unit\Types;

use Fastbolt\EntityImporter\Types\ImportSourceDefinition;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Fastbolt\EntityImporter\Types\ImportSourceDefinition
 */
class ImportSourceDefinitionTest extends TestCase
{
    public function testDefaults(): void
    {
        $definition = new ImportSourceDefinition(
            'foo',
            'bar'
        );

        self::assertSame('foo', $definition->getFilename());
        self::assertSame(';', $definition->getDelimiter());
        self::assertSame('"', $definition->getEnclosure());
        self::assertSame('\\', $definition->getEscape());
        self::assertTrue($definition->hasHeaderRow());
        self::assertSame('bar', $definition->getType());
        self::assertNull($definition->getImportDir());
    }

    public function testCustomValues(): void
    {
        $definition = new ImportSourceDefinition(
            'foo',
            'bar',
            'a',
            'b',
            'c'
        );
        $definition->setHasHeaderRow(false)
                   ->setImportDir('/bar/dir');

        self::assertSame('foo', $definition->getFilename());
        self::assertSame('a', $definition->getDelimiter());
        self::assertSame('b', $definition->getEnclosure());
        self::assertSame('c', $definition->getEscape());
        self::assertFalse($definition->hasHeaderRow());
        self::assertSame('bar', $definition->getType());
        self::assertSame('/bar/dir', $definition->getImportDir());
    }
}
