<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Reader\Factory;

use Fastbolt\EntityImporter\EntityImporterDefinition;
use Fastbolt\EntityImporter\Reader\Reader\ReaderInterface;

interface ReaderFactoryInterface
{
    /**
     * @param EntityImporterDefinition $importerDefinition
     * @param array                    $options Array containing implementation-specific options
     *
     * @return ReaderInterface
     */
    public function getReader(EntityImporterDefinition $importerDefinition, array $options): ReaderInterface;

    /**
     * @param string $type
     *
     * @return bool
     */
    public function supportsType(string $type): bool;

    /**
     * @return string[]
     */
    public function getSupportedTypes(): array;
}
