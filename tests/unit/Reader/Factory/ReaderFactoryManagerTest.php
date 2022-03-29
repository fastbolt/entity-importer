<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Tests\Unit\Reader\Factory;

use Fastbolt\EntityImporter\Exceptions\UnsupportedReaderTypeException;
use Fastbolt\EntityImporter\Reader\Factory\ReaderFactoryInterface;
use Fastbolt\EntityImporter\Reader\Factory\ReaderFactoryManager;
use Fastbolt\TestHelpers\BaseTestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Fastbolt\EntityImporter\Reader\Factory\ReaderFactoryManager
 */
class ReaderFactoryManagerTest extends BaseTestCase
{
    /**
     * @var ReaderFactoryInterface|MockObject
     */
    private $readerFoo;

    /**
     * @var ReaderFactoryInterface|MockObject
     */
    private $readerBar;

    public function testGetReaderSuccess(): void
    {
        $manager = new ReaderFactoryManager([$this->readerFoo, $this->readerBar]);

        $reader = $manager->getReaderFactory('foo');
        self::assertSame($this->readerFoo, $reader);

        $reader = $manager->getReaderFactory('bar');
        self::assertSame($this->readerBar, $reader);
    }

    public function testGetReaderException(): void
    {
        $this->expectException(UnsupportedReaderTypeException::class);

        $manager = new ReaderFactoryManager([$this->readerFoo, $this->readerBar]);
        $manager->getReaderFactory('baz');
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->readerFoo = $this->getMock(ReaderFactoryInterface::class);
        $this->readerBar = $this->getMock(ReaderFactoryInterface::class);

        $this->readerFoo->method('supportsFiletype')
                        ->willReturnCallback(static function (string $type) {
                            return $type === 'foo';
                        });
        $this->readerBar->method('supportsFiletype')
                        ->willReturnCallback(static function (string $type) {
                            return $type === 'bar';
                        });
    }
}
