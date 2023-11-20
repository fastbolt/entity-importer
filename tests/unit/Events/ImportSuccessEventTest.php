<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Tests\Unit\Events;

use DateTime;
use Fastbolt\EntityImporter\EntityImporterDefinition;
use Fastbolt\EntityImporter\Events\ImportSuccessEvent;
use Fastbolt\EntityImporter\Types\ImportResult;
use Fastbolt\TestHelpers\BaseTestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Fastbolt\EntityImporter\Events\ImportSuccessEvent
 */
class ImportSuccessEventTest extends BaseTestCase
{
    /**
     * @var ImportResult|MockObject
     */
    private $importResult;

    /**
     * @var EntityImporterDefinition|MockObject
     */
    private $definition;

    public function testEvent(): void
    {
        $event = new ImportSuccessEvent($this->definition, $start = new DateTime(), $this->importResult);

        self::assertSame($this->definition, $event->getDefinition());
        self::assertSame($this->importResult, $event->getImportResult());
        self::assertSame($start, $event->getImportStart());
    }

    protected function setUp(): void
    {
        $this->importResult = $this->getMock(ImportResult::class);
        $this->definition   = $this->getMock(EntityImporterDefinition::class);
    }
}
