<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Reader\Factory;

use Fastbolt\EntityImporter\Types\ImportSourceDefinition;
use Port\Csv\CsvReader;
use SplFileObject;

class CsvReaderFactory implements ReaderFactoryInterface
{
    /**
     * @param ImportSourceDefinition $sourceDefinition
     * @param string                 $importFilePath
     *
     * @return CsvReader
     */
    public function getReader(ImportSourceDefinition $sourceDefinition, string $importFilePath): CsvReader
    {
        $fileObject = new SplFileObject($importFilePath);

        return new CsvReader(
            $fileObject,
            $sourceDefinition->getDelimiter(),
            $sourceDefinition->getEnclosure(),
            $sourceDefinition->getEscape()
        );
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function supportsFiletype(string $type): bool
    {
        return $type === 'csv';
    }
}
