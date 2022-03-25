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
     * @param string $type
     */
    public function __construct(string $type)
    {
        $this->type = $type;

        $message = sprintf(
            'Unsupported reader type: %s.',
            $type
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
}
