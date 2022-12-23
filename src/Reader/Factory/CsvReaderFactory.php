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
     * @param array                    $options
     *
     * @return CsvReader
     */
    public function getReader(EntityImporterDefinition $importerDefinition, array $options): CsvReader
    {
        $sourceDefinition = $importerDefinition->getImportSourceDefinition();
        $importFilePath   = $sourceDefinition->getTarget();
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
