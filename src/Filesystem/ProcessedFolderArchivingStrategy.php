<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Filesystem;

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
     * @param string $originalFilename
     *
     * @return string
     */
    public function archiveFile(string $originalFilename): string
    {
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

        return $archiveFilename;
    }
}
