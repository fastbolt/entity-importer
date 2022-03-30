<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Tests\Unit\Filesystem;

use Fastbolt\EntityImporter\Filesystem\ProcessedFolderArchivingStrategy;
use Fastbolt\TestHelpers\BaseTestCase;
use Fastbolt\TestHelpers\Visibility;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @covers \Fastbolt\EntityImporter\Filesystem\ProcessedFolderArchivingStrategy
 */
class ProcessedFolderArchivingStrategyTest extends BaseTestCase
{
    /**
     * @var Filesystem&MockObject
     */
    private $filesystem;

    public function testFallbackFilesystem(): void
    {
        $strategy = new ProcessedFolderArchivingStrategy('', '', null);

        self::assertInstanceOf(Filesystem::class, Visibility::getProperty($strategy, 'filesystem'));
    }

    public function testArchiveFile(): void
    {
        $sourceFolder  = __DIR__ . '/../../../var';
        $archiveFolder = $sourceFolder . '/archive';
        $strategy      = new ProcessedFolderArchivingStrategy($archiveFolder, 'Y-m-d\TH-i-s', null);
        if (!is_dir($archiveFolder)) {
            mkdir($archiveFolder, 0777, true);
        }

        $inputFile = $sourceFolder . '/input.txt';
        file_put_contents($inputFile, 'test');

        self::assertFileExists($inputFile);

        $result = $strategy->archiveFile($inputFile);

        self::assertMatchesRegularExpression(
            '/^input\.\d{4}-\d{2}-\d{2}T\d{2}-\d{2}-\d{2}\.txt\.gz$/',
            basename($result)
        );
        self::assertFileExists($result);
        self::assertFileDoesNotExist($inputFile);
        self::assertSame('test', gzdecode(file_get_contents($result)));
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->filesystem = $this->getMock(Filesystem::class);
    }
}
