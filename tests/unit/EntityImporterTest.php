<?php

namespace Fastbolt\EntityImporter\Tests\unit;

use Doctrine\Persistence\ObjectRepository;
use Fastbolt\EntityImporter\AbstractEntityImporterDefinition;
use Fastbolt\EntityImporter\Factory\ArrayToEntityFactory;
use Fastbolt\EntityImporter\Reader\ReaderFactory;
use Fastbolt\EntityImporter\Types\ImportSourceDefinition;
use PHPUnit\Framework\TestCase;
use Port\Csv\CsvReader;
use stdClass;

class EntityImporterTest extends TestCase
{
    private $readerFactory;

    private $defaultItemFactory;

    private $importerDefinition;

    private $repository;

    private $factory;

    private $reader;

    public function testImportUsesDefinitionFactory()
    {
        $data             = [
            [
                'col 1',
                'col 2',
                'col 3',
            ],
            [
                'val 1.1',
                'val 1.2',
                'val 1.3',
            ],
            [
                'val 2.1',
                'val 2.2',
                'val 2.3',
            ],
            null,
        ];
        $sourceDefinition = (new ImportSourceDefinition('filename.csv'));
        $this->importerDefinition->method('getImportSourceDefinition')
                                 ->willReturn($sourceDefinition);
        $this->importerDefinition->method('getRepository')
                                 ->willReturn($this->repository);
        $this->importerDefinition->method('getEntityFactory')
                                 ->willReturn($this->factory);
        $this->importerDefinition->method('getFields')
                                 ->willReturn($columnHeaders = ['foo', 'bar', 'asd']);
        $this->readerFactory->expects(self::once())
                            ->method('getReader')
                            ->with($sourceDefinition)
                            ->willReturn($this->reader);
        $this->reader->expects(self::once())
                     ->method('setColumnHeaders')
                     ->with($columnHeaders);
        $this->reader->method('current')
                     ->willReturnOnConsecutiveCalls(...$data);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->readerFactory      = $this->getMockBuilder(ReaderFactory::class)
                                         ->disableOriginalConstructor()
                                         ->getMock();
        $this->defaultItemFactory = $this->getMockBuilder(ArrayToEntityFactory::class)
                                         ->disableOriginalConstructor()
                                         ->getMock();
        $this->importerDefinition = $this->getMockBuilder(AbstractEntityImporterDefinition::class)
                                         ->disableOriginalConstructor()
                                         ->getMock();
        $this->repository         = $this->getMockBuilder(ObjectRepository::class)
                                         ->disableOriginalConstructor()
                                         ->getMock();
        $this->factory            = $this->getMockBuilder(stdClass::class)
                                         ->addMethods(['__invoke'])
                                         ->getMock();
        $this->reader             = $this->getMockBuilder(CsvReader::class)
                                         ->disableOriginalConstructor()
                                         ->getMock();
    }
}
