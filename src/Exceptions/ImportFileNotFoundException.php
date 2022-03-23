<?php

namespace Fastbolt\EntityImporter\Exceptions;

use Exception;

class ImportFileNotFoundException extends Exception
{
    /**
     * @var string
     */
    private string $importFile;

    /**
     * @param string $importFile
     */
    public function __construct(string $importFile)
    {
        $this->importFile = $importFile;

        $message = sprintf(
            'Import file %s does not exist',
            $importFile
        );
        parent::__construct($message);
    }

    /**
     * @return string
     */
    public function getImportFile(): string
    {
        return $this->importFile;
    }
}
