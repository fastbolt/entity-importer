<?php

namespace Fastbolt\EntityImporter\Tests\unit;

use Fastbolt\EntityImporter\EntityImporter;
use Fastbolt\EntityImporter\EntityImporterDefinition;
use Fastbolt\EntityImporter\EntityImporterManager;
use Fastbolt\EntityImporter\Exceptions\ImporterDefinitionNotFoundException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @covers \Fastbolt\EntityImporter\EntityImporterManager
 */
class EntityImporterManagerTest extends TestCase
{
    private MockObject | EntityImporter $importer;

    private EntityImporterDefinition | MockObject $definition1;

    private EntityImporterDefinition | MockObject $definition2;

    private $statusCallback;

    private $errorCallback;

    public function testImport(): void
    {
        $manager = new EntityImporterManager(
            $this->importer,
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

        $result = $manager->import('importer:def2:name', $this->statusCallback, $this->errorCallback);
    }

    public function testImportInvalidType(): void
    {
        $this->expectException(ImporterDefinitionNotFoundException::class);
        $this->expectExceptionMessage('Importer importer:def2:name not found.');
        $manager = new EntityImporterManager(
            $this->importer,
            []
        );
        self::assertSame([], $manager->getImporterDefinitions());
        $manager->import('importer:def2:name', $this->statusCallback, $this->errorCallback);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->importer = $this->getMockBuilder(EntityImporter::class)
                               ->disableOriginalConstructor()
                               ->onlyMethods(['import'])
                               ->getMock();

        $this->definition1 = $this->getMockBuilder(EntityImporterDefinition::class)
                                  ->disableOriginalConstructor()
                                  ->getMock();
        $this->definition1->method('getName')
                          ->willReturn('importer:def1:name');

        $this->definition2 = $this->getMockBuilder(EntityImporterDefinition::class)
                                  ->disableOriginalConstructor()
                                  ->getMock();
        $this->definition2->method('getName')
                          ->willReturn('importer:def2:name');
        $this->statusCallback = $this->getMockBuilder(stdClass::class)
                                     ->addMethods(['__invoke'])
                                     ->getMock();
        $this->errorCallback  = $this->getMockBuilder(stdClass::class)
                                     ->addMethods(['__invoke'])
                                     ->getMock();
    }
}
