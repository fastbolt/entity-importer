<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Tests\Unit\Exceptions;

use Fastbolt\EntityImporter\Exceptions\ImporterDefinitionNotFoundException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Fastbolt\EntityImporter\Exceptions\ImporterDefinitionNotFoundException
 */
class ImporterDefinitionNotFoundExceptionTest extends TestCase
{
    public function testPrimitives(): void
    {
        $exception = new ImporterDefinitionNotFoundException('foo:importer');
        self::assertSame('foo:importer', $exception->getName());
        self::assertSame('Importer foo:importer not found.', $exception->getMessage());
    }
}
