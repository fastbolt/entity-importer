<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Events;

use DateTimeInterface;
use Fastbolt\EntityImporter\EntityImporterDefinition;
use Fastbolt\EntityImporter\Types\ImportResult;

class ImportSuccessEvent
{
    /**
     * @var ImportResult
     */
    private ImportResult $importResult;

    /**
     * @var EntityImporterDefinition
     */
    private EntityImporterDefinition $definition;

    /**
     * @var DateTimeInterface
     */
    private DateTimeInterface $importStart;

    /**
     * @param EntityImporterDefinition $definition
     * @param DateTimeInterface        $importStart
     * @param ImportResult             $importResult
     */
    public function __construct(
        EntityImporterDefinition $definition,
        DateTimeInterface $importStart,
        ImportResult $importResult
    ) {
        $this->definition   = $definition;
        $this->importStart  = $importStart;
        $this->importResult = $importResult;
    }

    /**
     * @return ImportResult
     */
    public function getImportResult(): ImportResult
    {
        return $this->importResult;
    }

    /**
     * @return EntityImporterDefinition
     */
    public function getDefinition(): EntityImporterDefinition
    {
        return $this->definition;
    }

    /**
     * @return DateTimeInterface
     */
    public function getImportStart(): DateTimeInterface
    {
        return $this->importStart;
    }
}
