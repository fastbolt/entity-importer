<?php

namespace Fastbolt\EntityImporter\Reader\Factory;

use Fastbolt\EntityImporter\EntityImporterDefinition;
use Fastbolt\EntityImporter\Reader\Reader\ApiReader;
use Fastbolt\EntityImporter\Reader\Reader\ReaderInterface;

class ApiReaderFactory implements ReaderFactoryInterface
{
    public function getReader(EntityImporterDefinition $importerDefinition, array $options): ReaderInterface
    {
        return new ApiReader();
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
        return ['api'];
    }
}
