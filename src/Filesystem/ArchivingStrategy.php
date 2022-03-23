<?php

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
