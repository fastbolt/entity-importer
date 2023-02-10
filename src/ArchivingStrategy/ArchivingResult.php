<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\ArchivingStrategy;

class ArchivingResult
{
    /**
     * @var string|null
     */
    private ?string $archivedFilename;

    /**
     * @param string|null $archivedFilename
     */
    public function __construct(?string $archivedFilename = null)
    {
        $this->archivedFilename = $archivedFilename;
    }

    /**
     * @return string|null
     */
    public function getArchivedFilename(): ?string
    {
        return $this->archivedFilename;
    }
}
