<?php

namespace Fastbolt\EntityImporter\Tests\Unit\ArchivingStrategy;

use Fastbolt\EntityImporter\ArchivingStrategy\ArchivingResult;
use Fastbolt\TestHelpers\BaseTestCase;

/**
 * @covers \Fastbolt\EntityImporter\ArchivingStrategy\ArchivingResult
 */
class ArchivingResultTest extends BaseTestCase
{
    public function testAll(): void
    {
        $item = new ArchivingResult();
        self::assertNull($item->getArchivedFilename());

        $item = new ArchivingResult('foo');
        self::assertSame('foo', $item->getArchivedFilename());
    }
}
