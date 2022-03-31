<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Reader\Factory;

use Fastbolt\EntityImporter\EntityImporterDefinition;
use Fastbolt\EntityImporter\Reader\Reader\XlsxReader;
use SplFileObject;

class XlsxReaderFactory implements ReaderFactoryInterface
{
    /**
     * @param EntityImporterDefinition $importerDefinition
     * @param string                   $importFilePath
     *
     * @return XlsxReader
     */
    public function getReader(EntityImporterDefinition $importerDefinition, string $importFilePath): XlsxReader
    {
        $fileObject = new SplFileObject($importFilePath);

        return new XlsxReader(
            $fileObject,
            $importerDefinition->getFields(),
            $importerDefinition->getImportSourceDefinition()->hasHeaderRow() ? 1 : 0
        );
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function supportsFiletype(string $type): bool
    {
        return in_array($type, $this->getSupportedTypes());
    }

    /**
     * @return string[]
     */
    public function getSupportedTypes(): array
    {
        return ['xlsx'];
    }
}
