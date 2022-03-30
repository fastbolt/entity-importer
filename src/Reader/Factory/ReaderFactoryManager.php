<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Reader\Factory;

use Fastbolt\EntityImporter\Exceptions\UnsupportedReaderTypeException;

class ReaderFactoryManager
{
    /**
     * @var array<int,ReaderFactoryInterface>
     */
    private array $factories = [];

    /**
     * @param iterable<ReaderFactoryInterface> $factories
     */
    public function __construct(iterable $factories)
    {
        foreach ($factories as $factory) {
            $this->factories[] = $factory;
        }
    }

    /**
     * @param string $type
     *
     * @return ReaderFactoryInterface
     *
     * @throws UnsupportedReaderTypeException
     */
    public function getReaderFactory(string $type): ReaderFactoryInterface
    {
        foreach ($this->factories as $factory) {
            if ($factory->supportsFiletype($type)) {
                return $factory;
            }
        }

        throw new UnsupportedReaderTypeException($type, $this->getAvailableTypes());
    }

    /**
     * @return string[]
     */
    private function getAvailableTypes(): array
    {
        /** @var string[] $availableTypes */
        $availableTypes = [];
        array_walk(
            $this->factories,
            static function (ReaderFactoryInterface $readerFactory) use (&$availableTypes) {
                $availableTypes = array_merge($availableTypes, $readerFactory->getSupportedTypes());

                return $readerFactory;
            },
        );

        return $availableTypes;
    }
}
