<?php

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
