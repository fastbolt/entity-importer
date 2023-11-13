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
use Fastbolt\EntityImporter\Reader\Factory\XlsxReaderFactory;
use Fastbolt\EntityImporter\Types\ImportSourceDefinition\Xlsx;
use Fastbolt\TestHelpers\BaseTestCase;
use Fastbolt\TestHelpers\Visibility;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Fastbolt\EntityImporter\Reader\Factory\XlsxReaderFactory
 */
class XlsxReaderFactoryTest extends BaseTestCase
{
    /**
     * @var ArchivingStrategy&MockObject
     */
    private $archivingStrategy;

    public function testGetReader(): void
    {
        $sourceDefinition = (new Xlsx(
            __DIR__ . '/../../_Fixtures/Reader/Factory/XlsxReaderFactory/dummyFile.xlsx',
            $this->archivingStrategy
        ));
        $definition       = $this->getMock(EntityImporterDefinition::class);
        $definition->method('getImportSourceDefinition')
                   ->willReturn($sourceDefinition);
        $factory = new XlsxReaderFactory();
        $reader  = $factory->getReader($definition, []);

        self::assertSame(
            [['foo', 'bar', 'baz'], ['asd', 'asdf', 'asdfg'], ['x', 'xy', 'xyz']],
            Visibility::getProperty($reader, 'worksheet')
        );
        self::assertTrue($factory->supportsType('xlsx'));
    }

    public function testGetReaderUnknownFile(): void
    {
        $this->expectException(SourceUnavailableException::class);

        $sourceDefinition = (new Xlsx(
            __DIR__ . '/../../_Fixtures/Reader/Factory/XlsxReaderFactory/notExistingFile.xlsx',
            $this->archivingStrategy
        ));
        $definition       = $this->getMock(EntityImporterDefinition::class);
        $definition->method('getImportSourceDefinition')
                   ->willReturn($sourceDefinition);
        $factory = new XlsxReaderFactory();
        $factory->getReader($definition, []);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->archivingStrategy = $this->getMock(ArchivingStrategy::class);
    }
}
