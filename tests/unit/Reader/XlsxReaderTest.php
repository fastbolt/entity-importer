<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Tests\Unit\Reader;

use Fastbolt\EntityImporter\Reader\XlsxReader;
use Fastbolt\TestHelpers\BaseTestCase;
use Fastbolt\TestHelpers\Visibility;
use SplFileObject;

/**
 * @covers \Fastbolt\EntityImporter\Reader\XlsxReader
 */
class XlsxReaderTest extends BaseTestCase
{
    public function testXlsxReader(): void
    {
        $file          = new SplFileObject(
            __DIR__ . '/../../_Fixtures/Reader/Factory/XlsxReaderFactory/dummyFile.xlsx'
        );
        $columnHeaders = ['foo', 'bar', 'baz'];
        $reader        = new XlsxReader($file, $columnHeaders, 2);

        self::assertSame($columnHeaders, $reader->getColumnHeaders());
        self::assertSame(2, Visibility::getProperty($reader, 'headerRowNumber'));
        self::assertSame(
            [['foo', 'bar', 'baz'], ['asd', 'asdf', 'asdfg'], ['x', 'xy', 'xyz']],
            Visibility::getProperty($reader, 'worksheet')
        );

        Visibility::setProperty($reader, 'pointer', 1);
        Visibility::setProperty($reader, 'errors', $errors = ['foo', 'bar']);

        self::assertSame($errors, $reader->getErrors());
    }

    public function testIteratorInvalidContent(): void
    {
        $file          = new SplFileObject(
            __DIR__ . '/../../_Fixtures/Reader/Factory/XlsxReaderFactory/dummyFileInvalid.xlsx'
        );
        $columnHeaders = ['foo', 'bar'];
        $reader        = new XlsxReader($file, $columnHeaders, null, ';');

        self::assertSame(
            [
                ['foo', 'bar', 'baz'],
                ['asd', 'asdf', 'asdfg'],
                ['x', 'xy', 'xyz'],

            ],
            $reader->getErrors()
        );
    }

    public function testIteratorValidHeaders(): void
    {
        $file          = new SplFileObject(
            __DIR__ . '/../../_Fixtures/Reader/Factory/XlsxReaderFactory/dummyFile.xlsx'
        );
        $columnHeaders = ['a', 'b', 'c'];
        $reader        = new XlsxReader($file, $columnHeaders, null, ';');
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
        self::assertSame([], $reader->getErrors());
    }

    public function testIteratorValidHeadersInFile(): void
    {
        $file          = new SplFileObject(
            __DIR__ . '/../../_Fixtures/Reader/Factory/XlsxReaderFactory/dummyFile.xlsx'
        );
        $columnHeaders = ['a', 'b', 'c'];
        $reader        = new XlsxReader($file, $columnHeaders, 0, ';');
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
