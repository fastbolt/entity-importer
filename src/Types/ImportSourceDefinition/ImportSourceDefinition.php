<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Types\ImportSourceDefinition;

use Fastbolt\EntityImporter\ArchivingStrategy\ArchivingStrategy;

interface ImportSourceDefinition
{
    /**
     * @return string
     */
    public function getTarget(): string;

    /**
     * @return bool
     */
    public function skipFirstRow(): bool;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return array<string,mixed>
     */
    public function getOptions(): array;

    /**
     * @return ArchivingStrategy
     */
    public function getArchivingStrategy(): ArchivingStrategy;
}
