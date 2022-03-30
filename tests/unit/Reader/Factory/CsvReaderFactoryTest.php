<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Tests\Unit\Reader\Factory;

use Fastbolt\EntityImporter\EntityImporterDefinition;
use Fastbolt\EntityImporter\Reader\Factory\CsvReaderFactory;
use Fastbolt\EntityImporter\Types\ImportSourceDefinition;
use Fastbolt\TestHelpers\BaseTestCase;
use Fastbolt\TestHelpers\Visibility;
use SplFileObject;

/**
 * @covers \Fastbolt\EntityImporter\Reader\Factory\CsvReaderFactory
 */
class CsvReaderFactoryTest extends BaseTestCase
{
    public function testGetReader(): void
    {
        $sourceDefinition = new ImportSourceDefinition(
            'dummyFile.csv',
            'foo',
            '@',
            '`',
            '#'
        );
        $definition       = $this->getMock(EntityImporterDefinition::class);
        $definition->method('getImportSourceDefinition')
                   ->willReturn($sourceDefinition);
        $importFilePath = __DIR__ . '/../../_Fixtures/Reader/Factory/CsvReaderFactory/dummyFile.csv';
        $factory        = new CsvReaderFactory();
        $reader         = $factory->getReader($definition, $importFilePath);

        /** @var SplFileObject $file */
        $file = Visibility::getProperty($reader, 'file');

        self::assertSame('dummyFile.csv', $file->getFilename());
        self::assertSame(['@', '`', '#'], $file->getCsvControl());
        self::assertSame(0, Visibility::getProperty($reader, 'headerRowNumber'));

        self::assertTrue($factory->supportsFiletype('csv'));
    }

    public function testGetReaderWithoutHeaderRow(): void
    {
        $sourceDefinition = new ImportSourceDefinition(
            'dummyFile.csv',
            'foo',
            '@',
            '`',
            '#'
        );
        $sourceDefinition->setHasHeaderRow(false);
        $definition = $this->getMock(EntityImporterDefinition::class);
        $definition->method('getImportSourceDefinition')
                   ->willReturn($sourceDefinition);
        $importFilePath = __DIR__ . '/../../_Fixtures/Reader/Factory/CsvReaderFactory/dummyFile.csv';
        $factory        = new CsvReaderFactory();
        $reader         = $factory->getReader($definition, $importFilePath);

        self::assertSame(null, Visibility::getProperty($reader, 'headerRowNumber'));
    }
}
