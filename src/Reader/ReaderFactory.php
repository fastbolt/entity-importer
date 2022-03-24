<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Reader;

use Fastbolt\EntityImporter\Types\ImportSourceDefinition;
use Port\Csv\CsvReader;
use SplFileObject;

class ReaderFactory
{
    /**
     * @param ImportSourceDefinition $sourceDefinition
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
}
