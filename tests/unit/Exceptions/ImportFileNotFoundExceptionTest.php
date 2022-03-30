<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Tests\Unit\Exceptions;

use Fastbolt\EntityImporter\Exceptions\ImportFileNotFoundException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Fastbolt\EntityImporter\Exceptions\ImportFileNotFoundException
 */
class ImportFileNotFoundExceptionTest extends TestCase
{
    public function testException(): void
    {
        $exception = new ImportFileNotFoundException('/foo/bar.csv');
        self::assertSame('/foo/bar.csv', $exception->getImportFile());
        self::assertSame('Import file /foo/bar.csv does not exist', $exception->getMessage());
    }
}
