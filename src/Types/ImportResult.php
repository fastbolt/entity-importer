<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Types;

use Fastbolt\EntityImporter\ArchivingStrategy\ArchivingResult;

class ImportResult
{
    /**
     * @var int
     */
    private int $success = 0;

    /**
     * @var array<int,ImportError>
     */
    private array $errors = [];

    /**
     * @var ArchivingResult|null
     */
    private ?ArchivingResult $archivingResult = null;

    /**
     * @return $this
     */
    public function increaseSuccess(): self
    {
        $this->success++;

        return $this;
    }

    /**
     * @param ImportError $error
     *
     * @return $this
     */
    public function addError(ImportError $error): self
    {
        $this->errors[] = $error;

        return $this;
    }

    /**
     * @return int
     */
    public function getSuccess(): int
    {
        return $this->success;
    }

    /**
     * @return ImportError[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return ArchivingResult|null
     */
    public function getArchivingResult(): ?ArchivingResult
    {
        return $this->archivingResult;
    }

    /**
     * @param ArchivingResult|null $archivingResult
     *
     * @return ImportResult
     */
    public function setArchivingResult(?ArchivingResult $archivingResult): ImportResult
    {
        $this->archivingResult = $archivingResult;

        return $this;
    }
}
