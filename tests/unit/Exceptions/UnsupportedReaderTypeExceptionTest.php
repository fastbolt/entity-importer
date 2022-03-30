<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Tests\Unit\Exceptions;

use Fastbolt\EntityImporter\Exceptions\UnsupportedReaderTypeException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Fastbolt\EntityImporter\Exceptions\UnsupportedReaderTypeException
 */
class UnsupportedReaderTypeExceptionTest extends TestCase
{
    public function testException(): void
    {
        $exception = new UnsupportedReaderTypeException('foo');
        self::assertSame('foo', $exception->getType());
        self::assertStringMatchesFormat('Unsupported reader type: %s.', $exception->getMessage());
    }
}
