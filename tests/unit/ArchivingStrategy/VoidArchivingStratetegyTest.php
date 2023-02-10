<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Tests\Unit\ArchivingStrategy;

use Fastbolt\EntityImporter\ArchivingStrategy\VoidArchivingStratetegy;
use Fastbolt\EntityImporter\Types\ImportSourceDefinition\ImportSourceDefinition;
use Fastbolt\TestHelpers\BaseTestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Fastbolt\EntityImporter\ArchivingStrategy\VoidArchivingStratetegy
 */
class VoidArchivingStratetegyTest extends BaseTestCase
{
    /**
     * @var ImportSourceDefinition&MockObject
     */
    private $sourceDefinition;

    public function testAll(): void
    {
        $strategy = new VoidArchivingStratetegy();
        $result   = $strategy->archive($this->sourceDefinition);

        self::assertNull($result->getArchivedFilename());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->sourceDefinition = $this->getMock(ImportSourceDefinition::class);
    }
}
