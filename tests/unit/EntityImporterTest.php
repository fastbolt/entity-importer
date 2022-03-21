<?php

namespace Fastbolt\EntityImporter\Tests\unit;

use Doctrine\Persistence\ObjectRepository;
use Fastbolt\EntityImporter\AbstractEntityImporterDefinition;
use Fastbolt\EntityImporter\EntityImporter;
use Fastbolt\EntityImporter\Factory\ArrayToEntityFactory;
use Fastbolt\EntityImporter\Reader\ReaderFactory;
use Fastbolt\EntityImporter\Tests\_Helpers\BaseTestCase;
use Fastbolt\EntityImporter\Types\ImportSourceDefinition;
use Port\Csv\CsvReader;
use stdClass;

class EntityImporterTest extends BaseTestCase
{
    private $readerFactory;

    private $defaultItemFactory;

    private $importerDefinition;

    private $repository;

    private $customFactory;

    private $reader;

    private $statusCallback;

    private $errorCallback;

    /**
     * @covers \Fastbolt\EntityImporter\EntityImporter
     */
    public function testImportUsesDefaultFactory(): void
    {
        $data             = [
            [
                'foo' => 'col 1',
                'bar' => 'col 2',
                'asd' => 'col 3',
            ],
            [
                'foo' => 'val 1.1',
                'bar' => 'val 1.2',
                'asd' => 'val 1.3',
            ],
            [
                'foo' => 'val 2.1',
                'bar' => 'val 2.2',
                'asd' => 'val 2.3',
            ],
            null,
        ];
        $sourceDefinition = (new ImportSourceDefinition('filename.csv'));
        $this->importerDefinition->method('getImportSourceDefinition')
                                 ->willReturn($sourceDefinition);
        $this->importerDefinition->method('getRepository')
                                 ->willReturn($this->repository);
        $this->importerDefinition->method('getEntityFactory')
                                 ->willReturn($this->customFactory);
        $this->importerDefinition->method('getFields')
                                 ->willReturn($columnHeaders = ['foo', 'bar', 'asd']);
        $this->importerDefinition->method('getIdentifierColumns')
                                 ->willReturn(['bar']);
        $this->readerFactory->expects(self::once())
                            ->method('getReader')
                            ->with($sourceDefinition)
                            ->willReturn(
                                $this->mockIterator($this->reader, $data)
                            );
        $this->reader->expects(self::once())
                     ->method('setColumnHeaders')
                     ->with($columnHeaders);
        $this->repository->expects(self::exactly(2))
                         ->method('findOneBy')
                         ->withConsecutive(
                             [['bar' => 'val 1.2']],
                             [['bar' => 'val 2.2']],
                         )
                         ->willReturnOnConsecutiveCalls(
                             null,
                             null,
                             null
                         );
        $this->customFactory->expects(self::exactly(2))
                            ->method('__invoke')
                            ->withConsecutive(
                                [null, $data[1]],
                                [null, $data[2]],
                            );
        $this->defaultItemFactory->expects(self::never())
                                 ->method('__invoke');
        $this->statusCallback->expects(self::exactly(2))
                             ->method('__invoke');
        $this->errorCallback->expects(self::never())
                            ->method('__invoke');

        $importer = new EntityImporter($this->readerFactory, $this->defaultItemFactory);
        $result   = $importer->import($this->importerDefinition, $this->statusCallback, $this->errorCallback);
        self::assertSame(2, $result->getSuccess());
        self::assertCount(0, $result->getErrors());
        self::assertSame([], $result->getErrors());
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
        $this->customFactory      = $this->getMockBuilder(stdClass::class)
                                         ->addMethods(['__invoke'])
                                         ->getMock();
        $this->reader             = $this->getMockBuilder(CsvReader::class)
                                         ->disableOriginalConstructor()
                                         ->getMock();
        $this->statusCallback     = $this->getMockBuilder(stdClass::class)
                                         ->addMethods(['__invoke'])
                                         ->getMock();
        $this->errorCallback      = $this->getMockBuilder(stdClass::class)
                                         ->addMethods(['__invoke'])
                                         ->getMock();
    }
}
