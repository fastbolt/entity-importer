<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Types;

use Exception;

class ImportError extends Exception
{
    /**
     * @param int    $line
     * @param string $message
     */
    public function __construct(int $line, string $message)
    {
        $this->line = $line;

        parent::__construct($message);
    }
}
