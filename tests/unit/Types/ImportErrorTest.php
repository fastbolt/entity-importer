<?php

namespace Fastbolt\EntityImporter\Tests\Unit\Types;

use Fastbolt\EntityImporter\Types\ImportError;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Fastbolt\EntityImporter\Types\ImportError
 */
class ImportErrorTest extends TestCase
{
    public function testAll(): void
    {
        $error = new ImportError(15, 'foo bar');
        self::assertSame(15, $error->getLine());
        self::assertSame('foo bar', $error->getMessage());
    }
}
