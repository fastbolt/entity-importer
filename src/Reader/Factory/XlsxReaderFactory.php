<?php

namespace Fastbolt\EntityImporter\Reader\Factory;

use Fastbolt\EntityImporter\Types\ImportSourceDefinition;
use Port\Spreadsheet\SpreadsheetReader;
use SplFileObject;

class XlsxReaderFactory implements ReaderFactoryInterface
{
    /**
     * @param ImportSourceDefinition $sourceDefinition
     * @param string                 $importFilePath
     *
     * @return SpreadsheetReader
     */
    public function getReader(ImportSourceDefinition $sourceDefinition, string $importFilePath): SpreadsheetReader
    {
        $fileObject = new SplFileObject($importFilePath);

        return new SpreadsheetReader(
            $fileObject,
            $sourceDefinition->hasHeaderRow() ? 1 : 0
        );
    }

    /**
     * @inheritDoc
     */
    public function supportsFiletype(string $type): bool
    {
        return $type === 'xlsx';
    }
}
