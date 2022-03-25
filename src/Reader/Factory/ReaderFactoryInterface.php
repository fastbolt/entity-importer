<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Reader\Factory;

use Fastbolt\EntityImporter\Types\ImportSourceDefinition;

interface ReaderFactoryInterface
{
    /**
     * @param ImportSourceDefinition $sourceDefinition
     * @param string                 $importFilePath
     *
     * @return iterable
     */
    public function getReader(ImportSourceDefinition $sourceDefinition, string $importFilePath): iterable;

    /**
     * @param string $type
     *
     * @return bool
     */
    public function supportsFiletype(string $type): bool;
}
