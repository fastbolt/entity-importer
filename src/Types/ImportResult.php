<?php

namespace Fastbolt\EntityImporter\Types;

class ImportResult
{

    private int $success = 0;

    /**
     * @var array<int,ImportError>
     */
    private array $errors = [];

    public function increaseSuccess(): static
    {
        $this->success++;

        return $this;
    }

    public function addError(ImportError $error): static
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
}
