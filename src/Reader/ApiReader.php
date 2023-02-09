<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Reader;

use App\Domains\Import\DataWarehouse\Types\Structure\ApiResponse;
use Fastbolt\EntityImporter\EntityImporterDefinition;
use Fastbolt\EntityImporter\Reader\Api\PagePaginationStrategy;
use Fastbolt\EntityImporter\Reader\Api\PaginationStrategy;
use Fastbolt\EntityImporter\Reader\Reader\TKey;
use Fastbolt\EntityImporter\Types\ImportSourceDefinition\ImportSourceDefinition;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
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
        $this->options                = array_merge_recursive(
            $this->importSourceDefinition->getOptions(),
            $options
        );

        Assert::keyExists($this->options, 'api_key');
        if (!isset($this->options['pagination_strategy'])) {
            $this->options['pagination_strategy'] = new PagePaginationStrategy(500);
        }
    }

    /**
     * @return array<string|int,mixed>
     */
    public function current(): array
    {
        if ([] === $this->currentBatch) {
            $this->currentBatch = $this->loadBulkData($this->position);
        }

        if (null !== ($row = array_shift($this->currentBatch))) {
            return $row;
        }

        return [];
    }

    private function loadBulkData(int $offset): array
    {
        $clientFactory = $this->clientFactory;
        $client        = $clientFactory();

        /** @var PaginationStrategy $paginationStrategy */
        $paginationStrategy   = $this->options['pagination_strategy'];
        $paginationParameters = $paginationStrategy->getRequestParameters($offset);
        $requestParameters    = array_merge_recursive(
            [
                'verify'  => false,
                'headers' => [
                    'Accept'       => 'application/json',
                    'X-AUTH-TOKEN' => $this->importSourceDefinition->getOptions()['api_key'],
                ],
            ],
            $paginationParameters
        );
        $url                  = $this->importSourceDefinition->getSource();
        $requestMethod        = Request::METHOD_GET;

        try {
            /** @var Response $response */
            $response = $client->request(
                $requestMethod,
                $url,
                $requestParameters
            );
        } catch (RequestException $exception) {
            $response = $exception->getResponse();
            throw new HttpException(
                $response ? $response->getStatusCode() : 0,
                sprintf(
                    'Connection error (%s request to %s returned %s (%s)) (%s).',
                    $requestMethod,
                    $url,
                    $response ? $response->getStatusCode() : 'null',
                    $response ? $response->getReasonPhrase() : 'empty response',
                    $exception->getMessage()
                )
            );
        }

        if ($response->getStatusCode() !== HttpResponse::HTTP_OK) {
            throw new HttpException(
                $response->getStatusCode(),
                sprintf(
                    'Connection error (%s request to %s returned %s (%s)).',
                    $requestMethod,
                    $url,
                    $response->getStatusCode(),
                    $response->getReasonPhrase()
                )
            );
        }

        if (($body = $response->getBody()->getContents()) === '') {
            throw new HttpException(
                $response->getStatusCode(),
                sprintf(
                    'Connection error (%s request to %s returned %s (%s), but resulted in empty data.).',
                    $requestMethod,
                    $url,
                    $response->getStatusCode(),
                    $response->getReasonPhrase()
                )
            );
        }

        /** @var ApiResponse $result */
        $result = $this->serializer->deserialize($body, ApiResponse::class, 'json');

        dd($requestParameters, $url);
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
