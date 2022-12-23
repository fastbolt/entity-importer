<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\ArchivingStrategy;

use Fastbolt\EntityImporter\Types\ImportSourceDefinition\ImportSourceDefinition;
use Symfony\Component\Filesystem\Filesystem;

class ProcessedFolderArchivingStrategy implements ArchivingStrategy
{
    /**
     * @var string
     */
    private string $processedFolder;

    /**
     * @var string
     */
    private string $archiveFilenameDateFormat;

    /**
     * @var Filesystem
     */
    private Filesystem $filesystem;

    /**
     * @param string $processedFolder
     */
    public function __construct(
        string $processedFolder,
        string $archiveFilenameDateFormat,
        ?Filesystem $filesystem = null
    ) {
        $this->processedFolder           = $processedFolder;
        $this->archiveFilenameDateFormat = $archiveFilenameDateFormat;
        $this->filesystem                = $filesystem ?? new Filesystem();
    }

    /**
     * @param ImportSourceDefinition $importSourceDefinition
     *
     * @return ArchivingResult
     */
    public function archive(ImportSourceDefinition $importSourceDefinition): ArchivingResult
    {
        $originalFilename = $importSourceDefinition->getTarget();

        /** @var array{filename: string, extension: string} $pathInfo */
        $pathInfo = pathinfo($originalFilename);
        [
            'filename'  => $filename,
            'extension' => $extension,
        ] = $pathInfo;

        $archiveFilename = sprintf(
            '%s/%s/%s/%s.%s.%s.gz',
            $this->processedFolder,
            date('Y'),
            date('m'),
            $filename,
            date($this->archiveFilenameDateFormat),
            $extension
        );

        $fileContents = gzencode(file_get_contents($originalFilename));

        $this->filesystem->dumpFile($archiveFilename, $fileContents);
        $this->filesystem->remove($originalFilename);

        return new ArchivingResult($archiveFilename);
    }
}
