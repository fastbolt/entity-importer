<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Types;

class ImportError
{
    /**
     * @var int
     */
    private int $line;

    /**
     * @var string
     */
    private string $message;

    /**
     * @param int    $line
     * @param string $message
     */
    public function __construct(int $line, string $message)
    {
        $this->line    = $line;
        $this->message = $message;
    }

    /**
     * @return int
     */
    public function getLine(): int
    {
        return $this->line;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}
