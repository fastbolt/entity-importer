<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Reader;

use Iterator;

/**
 * @extends Iterator<int,mixed>
 */
interface ReaderInterface extends Iterator
{
    /**
     * @return array<int,array<int,mixed>>
     */
    public function getErrors(): array;
}
