<?php

namespace Fastbolt\EntityImporter\Exceptions;

use InvalidArgumentException;

class ImporterDefinitionNotFoundException extends InvalidArgumentException
{
    private const MESSAGE = 'Importer %s not found.';

    /**
     * @var string
     */
    private string $name;

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;

        parent::__construct(sprintf(self::MESSAGE, $name));
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
