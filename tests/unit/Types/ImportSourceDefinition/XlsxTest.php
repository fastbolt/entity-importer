<?php

namespace Fastbolt\EntityImporter\Tests\Unit\Types\ImportSourceDefinition;

use Fastbolt\EntityImporter\ArchivingStrategy\ArchivingStrategy;
use Fastbolt\EntityImporter\Types\ImportSourceDefinition\Xlsx;
use Fastbolt\TestHelpers\BaseTestCase;

/**
 * @covers \Fastbolt\EntityImporter\Types\ImportSourceDefinition\Xlsx
 */
class XlsxTest extends BaseTestCase
{
    private $archivingStrategy;

    public function testDefaults(): void
    {
        $definition = new Xlsx(
            'foo',
            $this->archivingStrategy
        );

        self::assertSame('foo', $definition->getTarget());
        self::assertTrue($definition->skipFirstRow());
        self::assertSame('xlsx', $definition->getType());
        self::assertSame([], $definition->getOptions());
        self::assertSame($this->archivingStrategy, $definition->getArchivingStrategy());
    }

    public function testCustomValues(): void
    {
        $definition = new Xlsx(
            'foo',
            $this->archivingStrategy,
            false
        );
        self::assertSame('foo', $definition->getTarget());
        self::assertFalse($definition->skipFirstRow());
        self::assertSame('xlsx', $definition->getType());
        self::assertSame([], $definition->getOptions());
        self::assertSame($this->archivingStrategy, $definition->getArchivingStrategy());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->archivingStrategy = $this->getMock(ArchivingStrategy::class);
    }
}
