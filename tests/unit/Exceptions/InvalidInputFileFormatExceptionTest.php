<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Tests\Unit\Exceptions;

use Fastbolt\EntityImporter\Exceptions\InvalidInputFileFormatException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Fastbolt\EntityImporter\Exceptions\InvalidInputFileFormatException
 */
class InvalidInputFileFormatExceptionTest extends TestCase
{
    public function testException(): void
    {
        $exception = new InvalidInputFileFormatException('foo/filename.txt', $errors = ['foo', 'bar']);
        self::assertSame('foo/filename.txt', $exception->getFilename());
        self::assertSame($errors, $exception->getErrors());
        self::assertStringMatchesFormat(
            'Invalid input file format for file: %s. %d errors found.',
            $exception->getMessage()
        );
    }
}
