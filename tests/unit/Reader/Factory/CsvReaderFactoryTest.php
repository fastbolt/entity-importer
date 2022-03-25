<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Tests\Unit\Reader\Factory;

use Fastbolt\EntityImporter\Reader\Factory\CsvReaderFactory;
use Fastbolt\EntityImporter\Types\ImportSourceDefinition;
use Fastbolt\TestHelpers\Visibility;
use PHPUnit\Framework\TestCase;
use SplFileObject;

/**
 * @covers \Fastbolt\EntityImporter\Reader\Factory\CsvReaderFactory
 */
class CsvReaderFactoryTest extends TestCase
{
    public function testGetReader(): void
    {
        $definition     = new ImportSourceDefinition(
            'dummyFile.csv',
            'foo',
            '@',
            '`',
            '#'
        );
        $importFilePath = __DIR__ . '/../../_Fixtures/Reader/Factory/CsvReaderFactory/dummyFile.csv';
        $factory        = new CsvReaderFactory();
        $reader         = $factory->getReader($definition, $importFilePath);
        /** @var SplFileObject $file */
        $file = Visibility::getProperty($reader, 'file');

        self::assertSame('dummyFile.csv', $file->getFilename());
        self::assertSame(['@', '`', '#'], $file->getCsvControl());

        self::assertTrue($factory->supportsFiletype('csv'));
    }
}
