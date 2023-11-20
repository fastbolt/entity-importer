<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Events;

use DateTimeInterface;
use Exception;
use Fastbolt\EntityImporter\EntityImporterDefinition;

class ImportFailureEvent
{
    /**
     * @var Exception
     */
    private Exception $exception;

    /**
     * @var EntityImporterDefinition|null
     */
    private ?EntityImporterDefinition $definition;

    /**
     * @var DateTimeInterface
     */
    private DateTimeInterface $importStart;

    /**
     * @param EntityImporterDefinition|null $definition
     * @param DateTimeInterface             $importStart
     * @param Exception                     $exception
     */
    public function __construct(
        ?EntityImporterDefinition $definition,
        DateTimeInterface $importStart,
        Exception $exception
    ) {
        $this->definition  = $definition;
        $this->importStart = $importStart;
        $this->exception   = $exception;
    }

    /**
     * @return Exception
     */
    public function getException(): Exception
    {
        return $this->exception;
    }

    /**
     * @return EntityImporterDefinition|null
     */
    public function getDefinition(): ?EntityImporterDefinition
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
