<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Types\ImportSourceDefinition;

use Fastbolt\EntityImporter\ArchivingStrategy\ArchivingStrategy;

class Xlsx implements ImportSourceDefinition
{
    /**
     * @var string
     */
    private string $importFile;

    /**
     * @var bool
     */
    private bool $hasHeaderRow;

    /**
     * @var ArchivingStrategy
     */
    private ArchivingStrategy $archivingStrategy;

    /**
     * @var bool
     */
    private bool $throwOnSourceUnavailable = true;

    /**
     * @param string            $importFile
     * @param ArchivingStrategy $archivingStrategy
     * @param bool              $hasHeaderRow
     */
    public function __construct(
        string $importFile,
        ArchivingStrategy $archivingStrategy,
        bool $hasHeaderRow = true
    ) {
        $this->importFile        = $importFile;
        $this->archivingStrategy = $archivingStrategy;
        $this->hasHeaderRow      = $hasHeaderRow;
    }

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->importFile;
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
        return 'xlsx';
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

    public function throwOnSourceUnavailable(): bool
    {
        return $this->throwOnSourceUnavailable;
    }

    public function setThrowOnSourceUnavailable(bool $throwOnSourceUnavailable): void
    {
        $this->throwOnSourceUnavailable = $throwOnSourceUnavailable;
    }
}
