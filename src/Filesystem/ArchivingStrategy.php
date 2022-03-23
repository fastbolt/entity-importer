<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Filesystem;

interface ArchivingStrategy
{
    /**
     * @param string $originalFilename
     *
     * @return string
     */
    public function archiveFile(string $originalFilename): string;
}
