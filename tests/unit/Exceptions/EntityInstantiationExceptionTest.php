<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Tests\Unit\Exceptions;

use Fastbolt\EntityImporter\Exceptions\EntityInstantiationException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Fastbolt\EntityImporter\Exceptions\EntityInstantiationException
 */
class EntityInstantiationExceptionTest extends TestCase
{
    public function testException()
    {
        $exception = new EntityInstantiationException(self::class, 5);
        self::assertSame(self::class, $exception->getEntityClass());
        self::assertSame(5, $exception->getNumRequiredParameters());
        self::assertStringMatchesFormat(
            'Unable to create new entity %s, constructor has %d required parameters.',
            $exception->getMessage()
        );
    }
}
