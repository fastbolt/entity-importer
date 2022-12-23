<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Types\ImportSourceDefinition;

use Fastbolt\EntityImporter\ArchivingStrategy\ArchivingStrategy;
use Fastbolt\EntityImporter\ArchivingStrategy\VoidArchivingStratetegy;

class Api implements ImportSourceDefinition
{
    private string $apiHost;

    private string $apiPath;

    /**
     * @param string $apiHost
     * @param string $apiPath
     */
    public function __construct(string $apiHost, string $apiPath)
    {
        $this->apiHost = $apiHost;
        $this->apiPath = $apiPath;
    }

    /**
     * @return string
     */
    public function getTarget(): string
    {
        return sprintf(
            '%s/%s',
            rtrim($this->apiHost, '/'),
            ltrim($this->apiPath, '/')
        );
    }

    /**
     * @return bool
     */
    public function skipFirstRow(): bool
    {
        return false;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'api';
    }

    /**
     * @return array<string,mixed>
     */
    public function getOptions(): array
    {
        return [];
    }

    /**
     * @return ArchivingStrategy
     */
    public function getArchivingStrategy(): ArchivingStrategy
    {
        return new VoidArchivingStratetegy();
    }
}
