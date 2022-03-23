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
     * @param string $processedFolder
     */
    public function __construct(string $processedFolder, string $archiveFilenameDateFormat)
    {
        $this->processedFolder           = $processedFolder;
        $this->archiveFilenameDateFormat = $archiveFilenameDateFormat;
    }

    /**
     * @param string $originalFilename
     *
     * @return string
     */
    public function archiveFile(string $originalFilename): string
    {
        $fs = new Filesystem();
        [
            'filename'  => $filename,
            'extension' => $extension,
        ] = pathinfo($originalFilename);

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

        $fs->dumpFile($archiveFilename, $fileContents);
        $fs->remove($originalFilename);

        return $archiveFilename;
    }
}
