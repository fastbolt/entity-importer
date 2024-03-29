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
     * @var array<string,mixed>
     */
    private array $options;

    private bool $throwOnSourceUnavailable = true;

    /**
     * @param string              $apiHost
     * @param string              $apiPath
     * @param array<string,mixed> $options
     */
    public function __construct(string $apiHost, string $apiPath, array $options)
    {
        $this->options = $options;
        $this->apiHost = $apiHost;
        $this->apiPath = $apiPath;
    }

    /**
     * @return string
     */
    public function getSource(): string
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
        return $this->options;
    }

    /**
     * @return ArchivingStrategy
     */
    public function getArchivingStrategy(): ArchivingStrategy
    {
        return new VoidArchivingStratetegy();
    }

    /**
     * @return bool
     */
    public function throwOnSourceUnavailable(): bool
    {
        return $this->throwOnSourceUnavailable;
    }

    /**
     * @param bool $throwOnSourceUnavailable
     *
     * @return void
     */
    public function setThrowOnSourceUnavailable(bool $throwOnSourceUnavailable): void
    {
        $this->throwOnSourceUnavailable = $throwOnSourceUnavailable;
    }
}
