<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Tests\Unit\Events;

use DateTime;
use Exception;
use Fastbolt\EntityImporter\EntityImporterDefinition;
use Fastbolt\EntityImporter\Events\ImportFailureEvent;
use Fastbolt\TestHelpers\BaseTestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Fastbolt\EntityImporter\Events\ImportFailureEvent
 */
class ImportFailureEventTest extends BaseTestCase
{
    /**
     * @var Exception|MockObject
     */
    private $exception;

    /**
     * @var EntityImporterDefinition|MockObject
     */
    private $definition;

    public function testEvent(): void
    {
        $event = new ImportFailureEvent($this->definition, $start = new DateTime(), $this->exception);

        self::assertSame($this->definition, $event->getDefinition());
        self::assertSame($start, $event->getImportStart());
        self::assertSame($this->exception, $event->getException());
    }

    protected function setUp(): void
    {
        $this->exception  = $this->getMock(Exception::class);
        $this->definition = $this->getMock(EntityImporterDefinition::class);
    }
}
