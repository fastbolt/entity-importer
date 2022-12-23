<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Reader\Factory;

use Fastbolt\EntityImporter\EntityImporterDefinition;
use Fastbolt\EntityImporter\Reader\Reader\ApiReader;
use Fastbolt\EntityImporter\Reader\Reader\ReaderInterface;

class ApiReaderFactory implements ReaderFactoryInterface
{
    /**
     * @param EntityImporterDefinition $importerDefinition
     * @param array                    $options
     *
     * @return ReaderInterface
     */
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
