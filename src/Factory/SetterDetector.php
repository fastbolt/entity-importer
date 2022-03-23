<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Factory;

interface SetterDetector
{
    /**
     * @param object $entity
     * @param string   $key
     * @param mixed    $value
     *
     * @return string|null
     */
    public function detectSetter($entity, string $key, $value): ?string;

    /**
     * @return int
     */
    public function getPriority(): int;
}
