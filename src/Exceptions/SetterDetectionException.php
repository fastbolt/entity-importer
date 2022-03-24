<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Exceptions;

use Exception;
use Fastbolt\EntityImporter\EntityImporterDefinition;

/**
 * @template T
 */
class SetterDetectionException extends Exception
{
    /**
     * @var EntityImporterDefinition<T>
     */
    private EntityImporterDefinition $definition;

    /**
     * @var string
     */
    private string $fieldName;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @param EntityImporterDefinition<T> $definition
     * @param string                      $fieldName
     * @param mixed                       $value
     */
    public function __construct(EntityImporterDefinition $definition, string $fieldName, $value)
    {
        $this->definition = $definition;
        $this->fieldName  = $fieldName;
        $this->value      = $value;

        $message = sprintf(
            'Could not detect setter for field `%s` (importer definition: `%s`)',
            $fieldName,
            get_class($definition)
        );
        parent::__construct($message);
    }

    /**
     * @return EntityImporterDefinition<T>
     */
    public function getDefinition(): EntityImporterDefinition
    {
        return $this->definition;
    }

    /**
     * @return string
     */
    public function getFieldName(): string
    {
        return $this->fieldName;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
