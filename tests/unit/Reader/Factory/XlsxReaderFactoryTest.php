<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Tests\Unit\Reader\Factory;

use Fastbolt\EntityImporter\Reader\Factory\XlsxReaderFactory;
use Fastbolt\EntityImporter\Types\ImportSourceDefinition;
use Fastbolt\TestHelpers\Visibility;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Fastbolt\EntityImporter\Reader\Factory\XlsxReaderFactory
 */
class XlsxReaderFactoryTest extends TestCase
{
    public function testGetReader(): void
    {
        $definition     = (new ImportSourceDefinition('dummyFile.xlsx','xlsx'))
            ->setHasHeaderRow(false);
        $importFilePath = __DIR__ . '/../../_Fixtures/Reader/Factory/XlsxReaderFactory/dummyFile.xlsx';
        $factory        = new XlsxReaderFactory();
        $reader         = $factory->getReader($definition, $importFilePath);
        $sheet          = Visibility::getProperty($reader, 'worksheet');

        self::assertSame([['foo', 'bar', 'baz']], $sheet);
        self::assertTrue($factory->supportsFiletype('xlsx'));
    }
}
