<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Tests\Unit\Reader\Factory;

use Fastbolt\EntityImporter\EntityImporterDefinition;
use Fastbolt\EntityImporter\Reader\Factory\ApiReaderFactory;
use Fastbolt\EntityImporter\Types\ImportSourceDefinition\Api;
use Fastbolt\TestHelpers\BaseTestCase;
use Fastbolt\TestHelpers\Visibility;
use GuzzleHttp\Client;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Fastbolt\EntityImporter\Reader\Factory\ApiReaderFactory
 */
class ApiReaderFactoryTest extends BaseTestCase
{
    /**
     * @var callable&MockObject
     */
    private $clientFactory;

    /**
     * @var EntityImporterDefinition&MockObject
     */
    private $importerDefinition;

    /**
     * @var Client&MockObject
     */
    private $client;

    public function testWithCustomClientFactory(): void
    {
        $factory = new ApiReaderFactory();
        $reader  = $factory->getReader($this->importerDefinition, ['api_key' => 'foo']);

        $clientFactory = Visibility::getProperty($reader, 'clientFactory');
        self::assertIsCallable($clientFactory);
        self::assertInstanceOf(Client::class, $clientFactory());

        self::assertTrue($factory->supportsType('api'));
        self::assertFalse($factory->supportsType('foo'));
        self::assertSame(['api'], $factory->getSupportedTypes());
    }

    public function testWithDefaultClientFactory(): void
    {
        $factory = new ApiReaderFactory($this->clientFactory);
        $reader  = $factory->getReader($this->importerDefinition, ['api_key' => 'foo']);

        $clientFactory = Visibility::getProperty($reader, 'clientFactory');
        self::assertIsCallable($clientFactory);
        self::assertSame($this->clientFactory, $clientFactory);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->clientFactory      = $this->getCallable();
        $this->importerDefinition = $this->getMock(Api::class);
        $this->client             = $this->getMock(Client::class);
    }
}
