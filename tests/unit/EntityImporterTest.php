<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Tests\Unit;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use Fastbolt\EntityImporter\AbstractEntityImporterDefinition;
use Fastbolt\EntityImporter\ArchivingStrategy\ArchivingStrategy;
use Fastbolt\EntityImporter\EntityImporter;
use Fastbolt\EntityImporter\Exceptions\InvalidInputFormatException;
use Fastbolt\EntityImporter\Factory\ArrayToEntityFactory;
use Fastbolt\EntityImporter\Reader\Factory\ReaderFactoryInterface;
use Fastbolt\EntityImporter\Reader\Factory\ReaderFactoryManager;
use Fastbolt\EntityImporter\Reader\Reader\ReaderInterface;
use Fastbolt\EntityImporter\Types\ImportSourceDefinition\Csv;
use Fastbolt\TestHelpers\BaseTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use stdClass;

/**
 * @covers \Fastbolt\EntityImporter\EntityImporter
 */
class EntityImporterTest extends BaseTestCase
{
    /**
     * @var ReaderFactoryInterface&MockObject
     */
    private $readerFactory;

    /**
     * @var ReaderFactoryManager&MockObject
     */
    private $readerFactoryManager;

    /**
     * @var ArrayToEntityFactory&MockObject
     */
    private $defaultItemFactory;

    /**
     * @var AbstractEntityImporterDefinition&MockObject
     */
    private $importerDefinition;

    /**
     * @var ObjectRepository&MockObject
     */
    private $repository;

    /**
     * @var ObjectManager&MockObject
     */
    private $objectManager;

    /**
     * @var ArchivingStrategy&MockObject
     */
    private $archivingStrategy;

    /**
     * @var MockObject&ReaderInterface
     */
    private $reader;

    /**
     * @var MockObject|stdClass|callable
     */
    private $customFactory;

    /**
     * @var MockObject|stdClass|callable
     */
    private $statusCallback;

    /**
     * @var MockObject|stdClass|callable
     */
    private $errorCallback;

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
        $object1          = new stdClass();
        $object2          = new stdClass();
        $sourceDefinition = (new Csv('dummyFile.csv', $this->archivingStrategy));
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
        $this->importerDefinition->method('getFlushInterval')
                                 ->willReturn(10);
        $this->readerFactoryManager->expects(self::once())
                                   ->method('getReaderFactory')
                                   ->with('csv')
                                   ->willReturn($this->readerFactory);
        $this->readerFactory->expects(self::once())
                            ->method('getReader')
                            ->with($this->importerDefinition)
                            ->willReturn(
                                $this->mockIterator($this->reader, $data)
                            );
        $this->reader->method('getErrors')
                     ->willReturn([]);
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
                                [$this->importerDefinition, null, $data[1]],
                                [$this->importerDefinition, null, $data[2]],
                            )
                            ->willReturnOnConsecutiveCalls(
                                $object1,
                                $object2
                            );
        $this->objectManager->expects(self::exactly(2))
                            ->method('persist')
                            ->withConsecutive(
                                [$object1],
                                [$object2]
                            );
        $this->defaultItemFactory->expects(self::never())
                                 ->method('__invoke');
        $this->statusCallback->expects(self::exactly(2))
                             ->method('__invoke');
        $this->errorCallback->expects(self::never())
                            ->method('__invoke');

        $importer = new EntityImporter(
            $this->readerFactoryManager,
            $this->defaultItemFactory,
            $this->objectManager
        );
        $result   = $importer->import($this->importerDefinition, $this->statusCallback, $this->errorCallback, null);
        self::assertSame(2, $result->getSuccess());
        self::assertCount(0, $result->getErrors());
        self::assertSame([], $result->getErrors());
    }

    public function testUsesEntityModifier(): void
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
            null,
        ];
        $object1          = new stdClass();
        $sourceDefinition = (new Csv('dummyFile.csv', $this->archivingStrategy));
        $this->importerDefinition->method('getImportSourceDefinition')
                                 ->willReturn($sourceDefinition);
        $this->importerDefinition->method('getRepository')
                                 ->willReturn($this->repository);
        $this->importerDefinition->method('getEntityFactory')
                                 ->willReturn($this->customFactory);
        $this->importerDefinition->method('getEntityModifier')
                                 ->willReturn(function ($entity, $data) {
                                     $entity->mod = true;

                                     return $entity;
                                 });
        $this->importerDefinition->method('getFields')
                                 ->willReturn($columnHeaders = ['foo', 'bar', 'asd']);
        $this->importerDefinition->method('getIdentifierColumns')
                                 ->willReturn(['bar']);
        $this->importerDefinition->method('getFlushInterval')
                                 ->willReturn(10);
        $this->readerFactoryManager->expects(self::once())
                                   ->method('getReaderFactory')
                                   ->with('csv')
                                   ->willReturn($this->readerFactory);
        $this->readerFactory->expects(self::once())
                            ->method('getReader')
                            ->with($this->importerDefinition)
                            ->willReturn(
                                $this->mockIterator($this->reader, $data)
                            );
        $this->reader->method('getErrors')
                     ->willReturn([]);
        $this->repository->expects(self::once())
                         ->method('findOneBy')
                         ->with(['bar' => 'val 1.2'])
                         ->willReturn(null);
        $this->customFactory->expects(self::once())
                            ->method('__invoke')
                            ->with($this->importerDefinition, null, $data[1])
                            ->willReturn($object1);
        $this->objectManager->expects(self::once())
                            ->method('persist')
                            ->with(
                                self::callback(static function ($object) {
                                    self::assertInstanceOf(stdClass::class, $object);
                                    self::assertTrue($object->mod);

                                    return true;
                                })
                            );
        $this->defaultItemFactory->expects(self::never())
                                 ->method('__invoke');
        $this->statusCallback->expects(self::once())
                             ->method('__invoke');
        $this->errorCallback->expects(self::never())
                            ->method('__invoke');

        $importer = new EntityImporter(
            $this->readerFactoryManager,
            $this->defaultItemFactory,
            $this->objectManager
        );
        $result   = $importer->import($this->importerDefinition, $this->statusCallback, $this->errorCallback, null);
        self::assertSame(1, $result->getSuccess());
        self::assertCount(0, $result->getErrors());
        self::assertSame([], $result->getErrors());
    }

    public function testInvalidInputFileDefinition(): void
    {
        $this->expectException(InvalidInputFormatException::class);

        $errors           = [
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
        ];
        $sourceDefinition = (new Csv('dummyFile.csv', $this->archivingStrategy));
        $this->importerDefinition->method('getImportSourceDefinition')
                                 ->willReturn($sourceDefinition);
        $this->importerDefinition->method('getRepository')
                                 ->willReturn($this->repository);
        $this->importerDefinition->method('getEntityFactory')
                                 ->willReturn($this->customFactory);
        $this->importerDefinition->method('getFields')
                                 ->willReturn($columnHeaders = ['foo', 'bar', 'asd', 'baz']);
        $this->importerDefinition->method('getIdentifierColumns')
                                 ->willReturn(['bar']);
        $this->importerDefinition->method('getFlushInterval')
                                 ->willReturn(1000);
        $this->readerFactoryManager->expects(self::once())
                                   ->method('getReaderFactory')
                                   ->with('csv')
                                   ->willReturn($this->readerFactory);
        $this->readerFactory->expects(self::once())
                            ->method('getReader')
                            ->with($this->importerDefinition)
                            ->willReturn(
                                $this->mockIterator($this->reader, [null])
                            );
        $this->reader->expects(self::once())
                     ->method('getErrors')
                     ->willReturn($errors);
        $this->repository->expects(self::never())
                         ->method('findOneBy');
        $this->customFactory->expects(self::never())
                            ->method('__invoke');
        $this->objectManager->expects(self::never())
                            ->method('persist');
        $this->defaultItemFactory->expects(self::never())
                                 ->method('__invoke');
        $this->statusCallback->expects(self::never())
                             ->method('__invoke');
        $this->errorCallback->expects(self::never())
                            ->method('__invoke');

        $importer = new EntityImporter(
            $this->readerFactoryManager,
            $this->defaultItemFactory,
            $this->objectManager
        );
        $result   = $importer->import($this->importerDefinition, $this->statusCallback, $this->errorCallback, null);
        self::assertSame(0, $result->getSuccess());
        self::assertCount(0, $result->getErrors());
        self::assertSame([], $result->getErrors());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->readerFactory        = $this->getMock(ReaderFactoryInterface::class);
        $this->defaultItemFactory   = $this->getMock(ArrayToEntityFactory::class);
        $this->objectManager        = $this->getMock(ObjectManager::class);
        $this->archivingStrategy    = $this->getMock(ArchivingStrategy::class);
        $this->importerDefinition   = $this->getMock(AbstractEntityImporterDefinition::class);
        $this->repository           = $this->getMock(ObjectRepository::class);
        $this->reader               = $this->getMock(ReaderInterface::class);
        $this->readerFactoryManager = $this->getMock(ReaderFactoryManager::class);
        $this->customFactory        = $this->getCallable();
        $this->statusCallback       = $this->getCallable();
        $this->errorCallback        = $this->getCallable();
    }
}
