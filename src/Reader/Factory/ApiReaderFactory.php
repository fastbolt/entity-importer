<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Reader\Factory;

use Fastbolt\EntityImporter\EntityImporterDefinition;
use Fastbolt\EntityImporter\Reader\ApiReader;
use GuzzleHttp\Client;

class ApiReaderFactory implements ReaderFactoryInterface
{
    /**
     * @var callable():Client
     */
    private $clientFactory;

    /**
     * @param callable():Client|null $clientFactory
     */
    public function __construct(?callable $clientFactory = null)
    {
        if ($clientFactory === null) {
            $clientFactory = static function (): Client {
                return new Client(['verify' => false]);
            };
        }
        $this->clientFactory = $clientFactory;
    }

    /**
     * @param EntityImporterDefinition $importerDefinition
     * @param array<string,mixed>      $options Array containing implementation-specific options
     *
     * @return ApiReader
     */
    public function getReader(EntityImporterDefinition $importerDefinition, array $options): ApiReader
    {
        return new ApiReader($importerDefinition, $options, $this->clientFactory);
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function supportsType(string $type): bool
    {
        return in_array($type, $this->getSupportedTypes());
    }

    /**
     * @return string[]
     */
    public function getSupportedTypes(): array
    {
        return ['api'];
    }
}
