<?php

namespace Fastbolt\EntityImporter\Types;

class ImportSourceDefinition
{

    private const TYPE_FILE = 'file';

    private string $filename;

    private string $type = self::TYPE_FILE;

    private string $delimiter;

    private string $enclosure;

    private string $escape;

    private bool $hasHeaderRow;

    private ?string $importDir = null;

    /**
     * @param string $filename
     * @param string $delimiter
     * @param string $enclosure
     * @param string $escape
     */
    public function __construct(
        string $filename,
        string $delimiter = ';',
        string $enclosure = '"',
        string $escape = '\\',
        bool $hasHeaderRow = true
    ) {
        $this->filename     = $filename;
        $this->delimiter    = $delimiter;
        $this->enclosure    = $enclosure;
        $this->escape       = $escape;
        $this->hasHeaderRow = $hasHeaderRow;
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @return string
     */
    public function getDelimiter(): string
    {
        return $this->delimiter;
    }

    /**
     * @return string
     */
    public function getEnclosure(): string
    {
        return $this->enclosure;
    }

    /**
     * @return string
     */
    public function getEscape(): string
    {
        return $this->escape;
    }

    /**
     * @return bool
     */
    public function hasHeaderRow(): bool
    {
        return $this->hasHeaderRow;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string|null
     */
    public function getImportDir(): ?string
    {
        return $this->importDir;
    }

    /**
     * @param string|null $importDir
     */
    public function setImportDir(?string $importDir): void
    {
        $this->importDir = $importDir;
    }
}
