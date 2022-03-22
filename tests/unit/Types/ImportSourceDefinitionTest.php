<?php

namespace Fastbolt\EntityImporter\Tests\unit\Types;

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
            'foo'
        );

        self::assertSame('foo', $definition->getFilename());
        self::assertSame(';', $definition->getDelimiter());
        self::assertSame('"', $definition->getEnclosure());
        self::assertSame('\\', $definition->getEscape());
        self::assertTrue($definition->hasHeaderRow());
        self::assertSame(ImportSourceDefinition::TYPE_FILE, $definition->getType());
        self::assertNull($definition->getImportDir());
    }

    public function testCustomValues(): void
    {
        $definition = new ImportSourceDefinition(
            'foo',
            'a',
            'b',
            'c'
        );
        $definition->setHasHeaderRow(false)
                   ->setType('foo')
                   ->setImportDir('/bar/dir');

        self::assertSame('foo', $definition->getFilename());
        self::assertSame('a', $definition->getDelimiter());
        self::assertSame('b', $definition->getEnclosure());
        self::assertSame('c', $definition->getEscape());
        self::assertFalse($definition->hasHeaderRow());
        self::assertSame('foo', $definition->getType());
        self::assertSame('/bar/dir', $definition->getImportDir());
    }
}
