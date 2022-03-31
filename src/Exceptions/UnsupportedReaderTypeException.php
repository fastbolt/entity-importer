<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Exceptions;

use Exception;

class UnsupportedReaderTypeException extends Exception
{
    /**
     * @var string
     */
    private string $type;

    /**
     * @var string[]
     */
    private array $availableTypes;

    /**
     * @param string   $type
     * @param string[] $availableTypes
     */
    public function __construct(string $type, array $availableTypes)
    {
        $this->type           = $type;
        $this->availableTypes = $availableTypes;

        $message = sprintf(
            'Unsupported reader type: %s. Available types: %s.',
            $type,
            implode(', ', $this->availableTypes ?: ['*none*'])
        );
        parent::__construct($message);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string[]
     */
    public function getAvailableTypes(): array
    {
        return $this->availableTypes;
    }
}
