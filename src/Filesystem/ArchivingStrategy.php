<?php

namespace Fastbolt\EntityImporter\Filesystem;

interface ArchivingStrategy
{
    public function archiveFile(string $originalFilename): string;
}
