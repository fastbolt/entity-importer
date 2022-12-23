<?php

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
