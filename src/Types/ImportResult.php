<?php

namespace Fastbolt\EntityImporter\Types;

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
     * @var string|null
     */
    private ?string $archivedFilePath = null;

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
     * @return string|null
     */
    public function getArchivedFilePath(): ?string
    {
        return $this->archivedFilePath;
    }

    /**
     * @param string|null $archivedFilePath
     *
     * @return ImportResult
     */
    public function setArchivedFilePath(?string $archivedFilePath): ImportResult
    {
        $this->archivedFilePath = $archivedFilePath;

        return $this;
    }
}
