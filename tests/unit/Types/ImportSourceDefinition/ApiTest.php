<?php

namespace Fastbolt\EntityImporter\Tests\Unit\Types\ImportSourceDefinition;

use Fastbolt\EntityImporter\ArchivingStrategy\VoidArchivingStratetegy;
use Fastbolt\EntityImporter\Types\ImportSourceDefinition\Api;
use Fastbolt\TestHelpers\BaseTestCase;

/**
 * @covers \Fastbolt\EntityImporter\Types\ImportSourceDefinition\Api
 */
class ApiTest extends BaseTestCase
{
    public function testAll(): void
    {
        $definition = new Api(
            'https://example.com',
            '/api/v1'
        );

        self::assertInstanceOf(VoidArchivingStratetegy::class, $definition->getArchivingStrategy());
        self::assertSame('https://example.com/api/v1', $definition->getTarget());
        self::assertSame([], $definition->getOptions());
        self::assertSame('api', $definition->getType());
        self::assertFalse($definition->skipFirstRow());
    }
}
