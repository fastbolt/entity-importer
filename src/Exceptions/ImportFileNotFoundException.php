<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
