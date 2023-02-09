<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Reader\Reader;

use Fastbolt\EntityImporter\EntityImporterDefinition;
use Fastbolt\EntityImporter\Types\ImportSourceDefinition\ImportSourceDefinition;
use GuzzleHttp\Client;
use Symfony\Component\Serializer\SerializerInterface;
use Webmozart\Assert\Assert;

class ApiReader implements ReaderInterface
{
    /**
     * @var callable
     */
    private $clientFactory;

    private SerializerInterface $serializer;

    private EntityImporterDefinition $importerDefinition;

    private ImportSourceDefinition $importSourceDefinition;

    private array $options;

    private array $currentBatch = [];

    private int $position = 0;

    public function __construct(
        SerializerInterface $serializer,
        EntityImporterDefinition $importerDefinition,
        array $options,
        ?callable $clientFactory = null
    ) {
        if (!$clientFactory) {
            $clientFactory = static function () {
                return new Client(['verify' => false]);
            };
        }
        $this->clientFactory          = $clientFactory;
        $this->serializer             = $serializer;
        $this->importerDefinition     = $importerDefinition;
        $this->importSourceDefinition = $importerDefinition->getImportSourceDefinition();
        $this->options                = $options;

        Assert::keyExists($this->importSourceDefinition->getOptions(), 'api_key');
    }

    /**
     * @return array<string|int,mixed>
     */
    public function current(): array
    {
        if ([] === $this->currentBatch) {
            $this->currentBatch = $this->loadData($this->position);
        }

        if (null !== ($row = array_shift($this->currentBatch))) {
            return $row;
        }

        return [];
    }

    private function loadData(int $offset): array
    {
        $clientFactory     = $this->clientFactory;
        $client            = $clientFactory();
        $requestParameters = [
            'verify'  => false,
            'headers' => [
                'Accept'       => 'application/json',
                'X-AUTH-TOKEN' => $this->importSourceDefinition->getOptions()['api_key'],
            ],
        ];
        dd($requestParameters);
    }

    /**
     * Move forward to next element
     *
     * @link https://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next(): void
    {
    }

    /**
     * Return the key of the current element
     *
     * @link https://php.net/manual/en/iterator.key.php
     * @return TKey|null TKey on success, or null on failure.
     */
    public function key(): int
    {
        return 0;
    }

    /**
     * Checks if current position is valid
     *
     * @link https://php.net/manual/en/iterator.valid.php
     * @return bool The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid(): bool
    {
        return true;
    }

    /**
     * Rewind the Iterator to the first element
     *
     * @link https://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind(): void
    {
    }

    /**
     * @return array<int,array<int,mixed>>
     */
    public function getErrors(): array
    {
        return [];
    }
}
