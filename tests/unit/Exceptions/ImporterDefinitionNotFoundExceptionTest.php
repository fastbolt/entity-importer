<?php

namespace Fastbolt\EntityImporter\Tests\unit\Exceptions;

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
