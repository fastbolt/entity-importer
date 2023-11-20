<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Reader\Factory;

use Fastbolt\EntityImporter\EntityImporterDefinition;
use Fastbolt\EntityImporter\Exceptions\SourceUnavailableException;
use Fastbolt\EntityImporter\Reader\ReaderInterface;

interface ReaderFactoryInterface
{
    /**
     * @param EntityImporterDefinition $importerDefinition
     * @param array<string,mixed>      $options Array containing implementation-specific options
     *
     * @return ReaderInterface
     *
     * @throws SourceUnavailableException
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
