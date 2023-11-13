<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Tests\Unit\Reader\Factory;

use Fastbolt\EntityImporter\ArchivingStrategy\ArchivingStrategy;
use Fastbolt\EntityImporter\EntityImporterDefinition;
use Fastbolt\EntityImporter\Exceptions\SourceUnavailableException;
use Fastbolt\EntityImporter\Reader\Factory\CsvReaderFactory;
use Fastbolt\EntityImporter\Types\ImportSourceDefinition\Csv;
use Fastbolt\TestHelpers\BaseTestCase;
use Fastbolt\TestHelpers\Visibility;
use PHPUnit\Framework\MockObject\MockObject;
use SplFileObject;

/**
 * @covers \Fastbolt\EntityImporter\Reader\Factory\CsvReaderFactory
 */
class CsvReaderFactoryTest extends BaseTestCase
{
    /***
     * @var ArchivingStrategy&MockObject
     */
    private $archivingStrategy;

    public function testGetReader(): void
    {
        $sourceDefinition = new Csv(
            __DIR__ . '/../../_Fixtures/Reader/Factory/CsvReaderFactory/dummyFile.csv',
            $this->archivingStrategy,
            '@',
            '`',
            '#'
        );
        /** @var EntityImporterDefinition&MockObject $definition */
        $definition = $this->getMock(EntityImporterDefinition::class);
        $definition->method('getImportSourceDefinition')
                   ->willReturn($sourceDefinition);
        $factory = new CsvReaderFactory();
        $reader  = $factory->getReader($definition, []);

        /** @var SplFileObject $file */
        $file = Visibility::getProperty($reader, 'file');

        self::assertSame('dummyFile.csv', $file->getFilename());
        self::assertSame(['@', '`', '#'], $file->getCsvControl());
        self::assertSame(0, Visibility::getProperty($reader, 'headerRowNumber'));

        self::assertTrue($factory->supportsType('csv'));
    }

    public function testGetReaderWithoutHeaderRow(): void
    {
        $sourceDefinition = new Csv(
            __DIR__ . '/../../_Fixtures/Reader/Factory/CsvReaderFactory/dummyFile.csv',
            $this->archivingStrategy,
            '@',
            '`',
            '#',
            false
        );
        $definition       = $this->getMock(EntityImporterDefinition::class);
        $definition->method('getImportSourceDefinition')
                   ->willReturn($sourceDefinition);
        $factory = new CsvReaderFactory();
        $reader  = $factory->getReader($definition, []);

        self::assertSame(null, Visibility::getProperty($reader, 'headerRowNumber'));
    }

    public function testGetReaderUnknownFile(): void
    {
        $this->expectException(SourceUnavailableException::class);

        $sourceDefinition = new Csv(
            __DIR__ . '/../../_Fixtures/Reader/Factory/CsvReaderFactory/notExistingFile.csv',
            $this->archivingStrategy,
            '@',
            '`',
            '#',
            false
        );
        $definition       = $this->getMock(EntityImporterDefinition::class);
        $definition->method('getImportSourceDefinition')
                   ->willReturn($sourceDefinition);
        $factory = new CsvReaderFactory();
        $factory->getReader($definition, []);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->archivingStrategy = $this->getMock(ArchivingStrategy::class);
    }
}
