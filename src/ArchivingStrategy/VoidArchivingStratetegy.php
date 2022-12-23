<?php

namespace Fastbolt\EntityImporter\ArchivingStrategy;

use Fastbolt\EntityImporter\Types\ImportSourceDefinition\ImportSourceDefinition;

class VoidArchivingStratetegy implements ArchivingStrategy
{
    public function archive(ImportSourceDefinition $sourceDefinition): ArchivingResult
    {
        return new ArchivingResult();
    }
}
