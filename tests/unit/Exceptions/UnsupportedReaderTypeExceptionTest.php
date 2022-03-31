<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Tests\Unit\Exceptions;

use Fastbolt\EntityImporter\Exceptions\UnsupportedReaderTypeException;
use Fastbolt\TestHelpers\BaseTestCase;

/**
 * @covers \Fastbolt\EntityImporter\Exceptions\UnsupportedReaderTypeException
 */
class UnsupportedReaderTypeExceptionTest extends BaseTestCase
{
    public function testException(): void
    {
        $exception = new UnsupportedReaderTypeException('foo', $availableTypes = ['asd', 'bar']);
        self::assertSame('foo', $exception->getType());
        self::assertSame($availableTypes, $exception->getAvailableTypes());
        self::assertStringMatchesFormat(
            'Unsupported reader type: foo. Available types: asd, bar.',
            $exception->getMessage()
        );
    }
}
