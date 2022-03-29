<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Reader\Factory;

use Fastbolt\EntityImporter\EntityImporterDefinition;
use Fastbolt\EntityImporter\Reader\Reader\CsvReader;
use SplFileObject;

class CsvReaderFactory implements ReaderFactoryInterface
{
    /**
     * @param EntityImporterDefinition $importerDefinition
     * @param string                   $importFilePath
     *
     * @return CsvReader
     */
    public function getReader(EntityImporterDefinition $importerDefinition, string $importFilePath): CsvReader
    {
        $fileObject       = new SplFileObject($importFilePath);
        $sourceDefinition = $importerDefinition->getImportSourceDefinition();

        return new CsvReader(
            $fileObject,
            $importerDefinition->getFields(),
            $sourceDefinition->hasHeaderRow() ? 0 : null,
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
