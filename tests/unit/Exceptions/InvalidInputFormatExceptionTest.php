<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Tests\Unit\Exceptions;

use Fastbolt\EntityImporter\Exceptions\InvalidInputFormatException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Fastbolt\EntityImporter\Exceptions\InvalidInputFormatException
 */
class InvalidInputFormatExceptionTest extends TestCase
{
    public function testException(): void
    {
        $exception = new InvalidInputFormatException('foo/filename.txt', $errors = ['foo', 'bar']);
        self::assertSame('foo/filename.txt', $exception->getSource());
        self::assertSame($errors, $exception->getErrors());
        self::assertStringMatchesFormat(
            'Invalid input format for source: %s. %d errors found.',
            $exception->getMessage()
        );
    }
}
