<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Reader\Factory;

use Fastbolt\EntityImporter\EntityImporterDefinition;
use Fastbolt\EntityImporter\Reader\CsvReader;
use Fastbolt\EntityImporter\Types\ImportSourceDefinition\Csv;
use SplFileObject;

class CsvReaderFactory implements ReaderFactoryInterface
{
    /**
     * @param EntityImporterDefinition $importerDefinition
     * @param array<string,mixed>      $options
     *
     * @return CsvReader
     */
    public function getReader(EntityImporterDefinition $importerDefinition, array $options): CsvReader
    {
        /** @var Csv $sourceDefinition */
        $sourceDefinition = $importerDefinition->getImportSourceDefinition();
        $importFilePath   = $sourceDefinition->getSource();
        $fileObject       = new SplFileObject($importFilePath);

        return new CsvReader(
            $fileObject,
            $importerDefinition->getFields(),
            $sourceDefinition->skipFirstRow() ? 0 : null,
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
    public function supportsType(string $type): bool
    {
        return in_array($type, $this->getSupportedTypes());
    }

    /**
     * @return string[]
     */
    public function getSupportedTypes(): array
    {
        return ['csv'];
    }
}
