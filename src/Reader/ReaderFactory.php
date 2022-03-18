<?php

namespace Fastbolt\EntityImporter\Reader;

use Fastbolt\EntityImporter\Types\ImportSourceDefinition;
use Port\Csv\CsvReader;
use SplFileObject;

class ReaderFactory
{
    /**
     * @var string
     */
    private string $importPath;

    /**
     * @param string $importPath
     */
    public function __construct(string $importPath)
    {
        $this->importPath = $importPath;
    }

    /**
     * @param ImportSourceDefinition $sourceDefinition
     *
     * @return CsvReader
     */
    public function getReader(ImportSourceDefinition $sourceDefinition): CsvReader
    {
        $fileObject = new SplFileObject(
            sprintf(
                '%s/%s',
                $sourceDefinition->getImportDir() ?? $this->importPath,
                $sourceDefinition->getFilename()
            )
        );

        return new CsvReader(
            $fileObject,
            $sourceDefinition->getDelimiter(),
            $sourceDefinition->getEnclosure(),
            $sourceDefinition->getEscape()
        );
    }
}
