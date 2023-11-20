<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Tests\Unit;

use Fastbolt\EntityImporter\EntityImporter;
use Fastbolt\EntityImporter\EntityImporterDefinition;
use Fastbolt\EntityImporter\EntityImporterManager;
use Fastbolt\EntityImporter\Events\ImportFailureEvent;
use Fastbolt\EntityImporter\Events\ImportSuccessEvent;
use Fastbolt\EntityImporter\Exceptions\ImporterDefinitionNotFoundException;
use Fastbolt\TestHelpers\BaseTestCase;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use stdClass;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * @covers \Fastbolt\EntityImporter\EntityImporterManager
 */
class EntityImporterManagerTest extends BaseTestCase
{
    /**
     * @var EntityImporter|MockObject
     */
    private $importer;

    /**
     * @var EntityImporterDefinition|MockObject
     */
    private $definition1;

    /**
     * @var EntityImporterDefinition|MockObject
     */
    private $definition2;

    /**
     * @var callable|MockObject|stdClass
     */
    private $statusCallback;

    /**
     * @var callable|MockObject|stdClass
     */
    private $errorCallback;

    /**
     * @var EventDispatcherInterface|MockObject
     */
    private $dispatcher;

    public function testImport(): void
    {
        $manager = new EntityImporterManager(
            $this->importer,
            $this->dispatcher,
            [
                $this->definition1,
                $this->definition2,
            ]
        );
        self::assertSame(
            [
                'importer:def1:name' => $this->definition1,
                'importer:def2:name' => $this->definition2,
            ],
            $manager->getImporterDefinitions()
        );
        $this->importer->expects(self::once())
                       ->method('import')
                       ->with($this->definition2, $this->statusCallback, $this->errorCallback);
        $this->dispatcher->expects(self::once())
                         ->method('dispatch')
                         ->with(self::isInstanceOf(ImportSuccessEvent::class));

        $result = $manager->import('importer:def2:name', $this->statusCallback, $this->errorCallback, null);
    }

    public function testImportInvalidType(): void
    {
        $this->expectException(ImporterDefinitionNotFoundException::class);
        $this->expectExceptionMessage('Importer importer:def2:name not found.');
        $manager = new EntityImporterManager(
            $this->importer,
            $this->dispatcher,
            []
        );
        $this->dispatcher->expects(self::once())
                         ->method('dispatch')
                         ->with(self::isInstanceOf(ImportFailureEvent::class));
        self::assertSame([], $manager->getImporterDefinitions());
        $manager->import('importer:def2:name', $this->statusCallback, $this->errorCallback, null);
    }

    public function testImportEmptyType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Name must not be empty');
        $manager = new EntityImporterManager(
            $this->importer,
            $this->dispatcher,
            []
        );
        $this->dispatcher->expects(self::once())
                         ->method('dispatch')
                         ->with(self::isInstanceOf(ImportFailureEvent::class));
        self::assertSame([], $manager->getImporterDefinitions());
        $manager->import('', $this->statusCallback, $this->errorCallback, null);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->importer       = $this->getMock(EntityImporter::class, ['import']);
        $this->dispatcher     = $this->getMock(EventDispatcherInterface::class);
        $this->definition1    = $this->getMock(EntityImporterDefinition::class);
        $this->definition2    = $this->getMock(EntityImporterDefinition::class);
        $this->statusCallback = $this->getCallable();
        $this->errorCallback  = $this->getCallable();

        $this->definition1->method('getName')
                          ->willReturn('importer:def1:name');
        $this->definition2->method('getName')
                          ->willReturn('importer:def2:name');
    }
}
