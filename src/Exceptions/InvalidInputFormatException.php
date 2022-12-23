<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Exceptions;

use Exception;

class InvalidInputFormatException extends Exception
{
    /**
     * @var string
     */
    private string $source;

    /**
     * @var array
     */
    private array $errors;

    /**
     * @param string $source
     * @param array  $errors
     */
    public function __construct(string $source, array $errors)
    {
        $this->source = $source;
        $this->errors = $errors;

        $message = sprintf(
            'Invalid input format for source: %s. %s errors found.',
            $source,
            count($errors)
        );
        parent::__construct($message);
    }

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
