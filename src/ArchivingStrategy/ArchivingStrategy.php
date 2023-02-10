<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\ArchivingStrategy;

use Fastbolt\EntityImporter\Types\ImportSourceDefinition\ImportSourceDefinition;

interface ArchivingStrategy
{
    /**
     * @param ImportSourceDefinition $importSourceDefinition
     *
     * @return ArchivingResult
     */
    public function archive(ImportSourceDefinition $importSourceDefinition): ArchivingResult;
}
