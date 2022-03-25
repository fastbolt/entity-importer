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
     * @var iterable<ReaderFactoryInterface>
     */
    private array $factories;

    /**
     * @param iterable<ReaderFactoryInterface> $factories
     */
    public function __construct(iterable $factories)
    {
        $this->factories = $factories;
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

        throw new UnsupportedReaderTypeException($type);
    }
}
