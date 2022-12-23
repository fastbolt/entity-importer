<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Types\ImportSourceDefinition;

use Fastbolt\EntityImporter\ArchivingStrategy\ArchivingStrategy;

class Csv implements ImportSourceDefinition
{
    /**
     * @var string
     */
    private string $importFile;

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
    private bool $hasHeaderRow;

    /**
     * @var ArchivingStrategy
     */
    private ArchivingStrategy $archivingStrategy;

    /**
     * @param string            $importFile
     * @param ArchivingStrategy $archivingStrategy
     * @param string            $delimiter
     * @param string            $enclosure
     * @param string            $escape
     * @param bool              $hasHeaderRow
     */
    public function __construct(
        string $importFile,
        ArchivingStrategy $archivingStrategy,
        string $delimiter = ';',
        string $enclosure = '"',
        string $escape = '\\',
        bool $hasHeaderRow = true
    ) {
        $this->importFile        = $importFile;
        $this->archivingStrategy = $archivingStrategy;
        $this->delimiter         = $delimiter;
        $this->enclosure         = $enclosure;
        $this->escape            = $escape;
        $this->hasHeaderRow      = $hasHeaderRow;
    }

    /**
     * @return string
     */
    public function getTarget(): string
    {
        return $this->importFile;
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
    public function skipFirstRow(): bool
    {
        return $this->hasHeaderRow;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'csv';
    }

    /**
     * @return array<string,mixed>
     */
    public function getOptions(): array
    {
        return [];
    }

    /**
     * @return ArchivingStrategy
     */
    public function getArchivingStrategy(): ArchivingStrategy
    {
        return $this->archivingStrategy;
    }
}
