<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Tests\Unit\Exceptions;

use Fastbolt\EntityImporter\Exceptions\SourceUnavailableException;
use Fastbolt\TestHelpers\BaseTestCase;

/**
 * @covers \Fastbolt\EntityImporter\Exceptions\SourceUnavailableException
 */
class SourceUnavailableExceptionTest extends BaseTestCase
{
    public function testItem(): void
    {
        $item = new SourceUnavailableException('test');

        self::assertSame('test', $item->getMessage());
    }
}
