<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Reader\Factory;

use Fastbolt\EntityImporter\EntityImporterDefinition;
use Fastbolt\EntityImporter\Exceptions\SourceUnavailableException;
use Fastbolt\EntityImporter\Reader\XlsxReader;
use SplFileObject;

class XlsxReaderFactory implements ReaderFactoryInterface
{
    /**
     * @param EntityImporterDefinition $importerDefinition
     * @param array<string,mixed>      $options Array containing implementation-specific options
     *
     * @return XlsxReader
     *
     * @throws SourceUnavailableException
     */
    public function getReader(EntityImporterDefinition $importerDefinition, array $options): XlsxReader
    {
        $sourceDefinition = $importerDefinition->getImportSourceDefinition();
        $importFilePath   = $sourceDefinition->getSource();

        if (!file_exists($importFilePath) || !is_file($importFilePath)) {
            throw new SourceUnavailableException(sprintf('File "%s" does not exist', $importFilePath));
        }

        $fileObject = new SplFileObject($importFilePath);

        return new XlsxReader(
            $fileObject,
            $importerDefinition->getFields(),
            $importerDefinition->getImportSourceDefinition()->skipFirstRow() ? 1 : 0
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
        return ['xlsx'];
    }
}
