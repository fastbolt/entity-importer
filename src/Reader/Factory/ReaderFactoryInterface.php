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
     * @param string                   $importFilePath
     *
     * @return ReaderInterface
     */
    public function getReader(EntityImporterDefinition $importerDefinition, string $importFilePath): ReaderInterface;

    /**
     * @param string $type
     *
     * @return bool
     */
    public function supportsFiletype(string $type): bool;
}
