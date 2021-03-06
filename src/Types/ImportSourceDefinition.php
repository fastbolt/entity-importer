<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Types;

class ImportSourceDefinition
{
    /**
     * @var string
     */
    private string $filename;

    /**
     * @var string
     */
    private string $type;

    /**
     * @var string
     */
    private string $delimiter;

    /**
     * @var string
     */
    private string $enclosure;

    /**
     * @var string
     */
    private string $escape;

    /**
     * @var bool
     */
    private bool $hasHeaderRow = true;

    /**
     * @var string|null
     */
    private ?string $importDir = null;

    /**
     * @param string $filename
     * @param string $delimiter
     * @param string $enclosure
     * @param string $escape
     */
    public function __construct(
        string $filename,
        string $type,
        string $delimiter = ';',
        string $enclosure = '"',
        string $escape = '\\'
    ) {
        $this->filename  = $filename;
        $this->type      = $type;
        $this->delimiter = $delimiter;
        $this->enclosure = $enclosure;
        $this->escape    = $escape;
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
     * @param bool $hasHeaderRow
     *
     * @return ImportSourceDefinition
     */
    public function setHasHeaderRow(bool $hasHeaderRow): ImportSourceDefinition
    {
        $this->hasHeaderRow = $hasHeaderRow;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
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
     *
     * @return ImportSourceDefinition
     */
    public function setImportDir(?string $importDir): ImportSourceDefinition
    {
        $this->importDir = $importDir;

        return $this;
    }
}
