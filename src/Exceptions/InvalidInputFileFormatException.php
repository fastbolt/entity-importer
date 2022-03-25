<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Exceptions;

use Exception;

class InvalidInputFileFormatException extends Exception
{
    /**
     * @var string
     */
    private string $filename;

    /**
     * @var array
     */
    private array $errors;

    /**
     * @param string $filename
     */
    public function __construct(string $filename, array $errors)
    {
        $this->filename = $filename;
        $this->errors   = $errors;

        $message = sprintf(
            'Invalid input file format for file: %s. %s errors found.',
            $filename,
            count($errors)
        );
        parent::__construct($message);
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
