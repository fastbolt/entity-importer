<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Exceptions;

use Exception;

class EntityInstantiationException extends Exception
{
    /**
     * @var string
     */
    private string $entityClass;

    /**
     * @var int
     */
    private int $numRequiredParameters;

    /**
     * @param string $entityClass
     * @param int    $numRequiredParameters
     */
    public function __construct(string $entityClass, int $numRequiredParameters)
    {
        $this->entityClass           = $entityClass;
        $this->numRequiredParameters = $numRequiredParameters;

        $message = sprintf(
            'Unable to create new entity %s, constructor has %s required parameters.',
            $entityClass,
            $numRequiredParameters
        );
        parent::__construct($message);
    }

    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return $this->entityClass;
    }

    /**
     * @return int
     */
    public function getNumRequiredParameters(): int
    {
        return $this->numRequiredParameters;
    }
}
