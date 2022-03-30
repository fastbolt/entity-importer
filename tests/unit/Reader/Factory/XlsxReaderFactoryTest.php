<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Tests\Unit\Reader\Factory;

use Fastbolt\EntityImporter\EntityImporterDefinition;
use Fastbolt\EntityImporter\Reader\Factory\XlsxReaderFactory;
use Fastbolt\EntityImporter\Types\ImportSourceDefinition;
use Fastbolt\TestHelpers\BaseTestCase;
use Fastbolt\TestHelpers\Visibility;

/**
 * @covers \Fastbolt\EntityImporter\Reader\Factory\XlsxReaderFactory
 */
class XlsxReaderFactoryTest extends BaseTestCase
{
    public function testGetReader(): void
    {
        $sourceDefinition = (new ImportSourceDefinition('dummyFile.xlsx', 'xlsx'))
            ->setHasHeaderRow(false);
        $definition       = $this->getMock(EntityImporterDefinition::class);
        $definition->method('getImportSourceDefinition')
                   ->willReturn($sourceDefinition);
        $importFilePath = __DIR__ . '/../../_Fixtures/Reader/Factory/XlsxReaderFactory/dummyFile.xlsx';
        $factory        = new XlsxReaderFactory();
        $reader         = $factory->getReader($definition, $importFilePath);

        self::assertSame(
            [['foo', 'bar', 'baz'], ['asd', 'asdf', 'asdfg'], ['x', 'xy', 'xyz']],
            Visibility::getProperty($reader, 'worksheet')
        );
        self::assertTrue($factory->supportsFiletype('xlsx'));
    }
}
