<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
