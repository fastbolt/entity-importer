<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Tests\Unit\Types;

use Fastbolt\EntityImporter\ArchivingStrategy\ArchivingResult;
use Fastbolt\EntityImporter\Types\ImportError;
use Fastbolt\EntityImporter\Types\ImportResult;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Fastbolt\EntityImporter\Types\ImportResult
 */
class ImportResultTest extends TestCase
{
    public function testAll(): void
    {
        $result = new ImportResult();

        self::assertSame([], $result->getErrors());
        self::assertSame(0, $result->getSuccess());
        self::assertNull($result->getArchivingResult());

        $result->increaseSuccess()
               ->addError($error1 = new ImportError(12, 'foo'))
               ->increaseSuccess()
               ->increaseSuccess()
               ->addError($error2 = new ImportError(15, 'bar'))
               ->setArchivingResult($archivingResult = new ArchivingResult());

        self::assertSame([$error1, $error2], $result->getErrors());
        self::assertSame(3, $result->getSuccess());
        self::assertSame($archivingResult, $result->getArchivingResult());
    }
}
