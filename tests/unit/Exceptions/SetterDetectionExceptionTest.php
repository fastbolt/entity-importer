<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Tests\Unit\Exceptions;

use Fastbolt\EntityImporter\EntityImporterDefinition;
use Fastbolt\EntityImporter\Exceptions\SetterDetectionException;
use Fastbolt\TestHelpers\BaseTestCase;

/**
 * @covers \Fastbolt\EntityImporter\Exceptions\SetterDetectionException
 */
class SetterDetectionExceptionTest extends BaseTestCase
{
    public function testException(): void
    {
        $definition = $this->getMock(EntityImporterDefinition::class);
        $exception  = new SetterDetectionException($definition, 'foo', 'bar');

        self::assertStringMatchesFormat('Could not detect setter for field `foo` (importer definition: `%s`)', $exception->getMessage());
        self::assertSame($definition, $exception->getDefinition());
        self::assertSame('foo', $exception->getFieldName());
        self::assertSame('bar', $exception->getValue());
    }
}
