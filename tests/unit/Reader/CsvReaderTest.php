<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Tests\Unit\Reader;

use Fastbolt\EntityImporter\Reader\CsvReader;
use Fastbolt\TestHelpers\BaseTestCase;
use Fastbolt\TestHelpers\Visibility;
use SplFileObject;

/**
 * @covers \Fastbolt\EntityImporter\Reader\CsvReader
 */
class CsvReaderTest extends BaseTestCase
{
    public function testCsvReader(): void
    {
        $file          = new SplFileObject(__DIR__ . '/../../_Fixtures/Reader/Factory/CsvReaderFactory/dummyFile.csv');
        $columnHeaders = ['foo', 'bar', 'baz'];
        $reader        = new CsvReader($file, $columnHeaders, null, ';', '`', '#');

        self::assertSame($columnHeaders, $reader->getColumnHeaders());

        /** @var SplFileObject $file */
        $file = Visibility::getProperty($reader, 'file');

        self::assertSame('dummyFile.csv', $file->getFilename());
        self::assertSame([';', '`', '#'], $file->getCsvControl());
    }

    public function testIteratorInvalidContent(): void
    {
        $file          = new SplFileObject(
            __DIR__ . '/../../_Fixtures/Reader/Factory/CsvReaderFactory/dummyFileInvalid.csv'
        );
        $columnHeaders = ['foo', 'bar', 'asd'];
        $reader        = new CsvReader($file, $columnHeaders, null, ';');

        self::assertSame(
            [1 => ['asd', 'asdfg']],
            $reader->getErrors()
        );
    }

    public function testIteratorValidHeaders(): void
    {
        $file          = new SplFileObject(
            __DIR__ . '/../../_Fixtures/Reader/Factory/CsvReaderFactory/dummyFile.csv'
        );
        $columnHeaders = ['a', 'b', 'c'];
        $reader        = new CsvReader($file, $columnHeaders, null, ';');
        $expectedData  = [
            ['a' => 'foo', 'b' => 'bar', 'c' => 'baz'],
            ['a' => 'asd', 'b' => 'asdf', 'c' => 'asdfg'],
            ['a' => 'x', 'b' => 'xy', 'c' => 'xyz'],
        ];

        foreach ($reader as $index => $row) {
            self::assertSame(
                $expectedData[$index],
                $row
            );
        }
    }

    public function testIteratorValidHeadersInFile(): void
    {
        $file          = new SplFileObject(
            __DIR__ . '/../../_Fixtures/Reader/Factory/CsvReaderFactory/dummyFile.csv'
        );
        $columnHeaders = ['a', 'b', 'c'];
        $reader        = new CsvReader($file, $columnHeaders, 0, ';');
        $expectedData  = [
            1 => ['a' => 'asd', 'b' => 'asdf', 'c' => 'asdfg'],
            2 => ['a' => 'x', 'b' => 'xy', 'c' => 'xyz'],
        ];

        foreach ($reader as $index => $row) {
            self::assertSame(
                $expectedData[$index],
                $row
            );
        }
    }
}
