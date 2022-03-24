<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Tests\Unit\Reader;

use Fastbolt\EntityImporter\Reader\ReaderFactory;
use Fastbolt\EntityImporter\Types\ImportSourceDefinition;
use Fastbolt\TestHelpers\Visibility;
use PHPUnit\Framework\TestCase;
use SplFileObject;

/**
 * @covers \Fastbolt\EntityImporter\Reader\ReaderFactory
 */
class ReaderFactoryTest extends TestCase
{
    public function testGetReaderWithImportPath(): void
    {
        $definition     = new ImportSourceDefinition(
            'dummyFile.csv',
            '@',
            '`',
            '#'
        );
        $importFilePath = __DIR__ . '/../_Fixtures/Reader/ReaderFactory/dummyFile.csv';
        $factory        = new ReaderFactory();
        $reader         = $factory->getReader($definition, $importFilePath);
        /** @var SplFileObject $file */
        $file = Visibility::getProperty($reader, 'file');

        self::assertSame('dummyFile.csv', $file->getFilename());
        self::assertSame(['@', '`', '#'], $file->getCsvControl());
    }
}
