<?php

namespace Fastbolt\EntityImporter\Tests\Unit\Reader;

use Fastbolt\EntityImporter\Reader\ReaderFactory;
use Fastbolt\EntityImporter\Types\ImportSourceDefinition;
use Fastbolt\TestHelpers\Visibility;
use PHPUnit\Framework\TestCase;
use SplFileObject;

/**
 * @covers \Fastbolt\EntityImporter\Reader\ReaderFactory
 */
class ReaderFactoryTest extends TestCase
{
    public function testGetReaderWithImportPath(): void
    {
        $definition = new ImportSourceDefinition(
            'dummyFile.csv',
            '@',
            '`',
            '#'
        );
        $definition->setImportDir(__DIR__ . '/../../_Fixtures/Reader/ReaderFactory/');
        $factory = new ReaderFactory('/foo/invalid/path/' . mt_rand());
        $reader  = $factory->getReader($definition);
        /** @var SplFileObject $file */
        $file = Visibility::getProperty($reader, 'file');

        self::assertSame('dummyFile.csv', $file->getFilename());
        self::assertSame(['@', '`', '#'], $file->getCsvControl());
    }

    public function testGetReaderWithoutImportPath(): void
    {
        $definition = new ImportSourceDefinition(
            'dummyFile.csv',
            '@',
            '`',
            '#'
        );
        $factory    = new ReaderFactory(__DIR__ . '/../../_Fixtures/Reader/ReaderFactory/');
        $reader     = $factory->getReader($definition);
        /** @var SplFileObject $file */
        $file = Visibility::getProperty($reader, 'file');

        self::assertSame('dummyFile.csv', $file->getFilename());
        self::assertSame(['@', '`', '#'], $file->getCsvControl());
    }
}
