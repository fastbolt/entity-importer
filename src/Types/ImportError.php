<?php

namespace Fastbolt\EntityImporter\Types;

class ImportError
{

    private int $line;

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
