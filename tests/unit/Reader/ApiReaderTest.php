<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Tests\Unit\Reader;

use Fastbolt\EntityImporter\Types\ImportSourceDefinition\ImportSourceDefinition;
use Fastbolt\TestHelpers\BaseTestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Fastbolt\EntityImporter\Reader\ApiReader
 */
class ApiReaderTest extends BaseTestCase
{
    /**
     * @var callable&MockObject
     */
    private $clientFactory;
    /**
     * @var ImporterDefinition&MockObject
     */
    private $importerDefinition;
    /**
     * @var ImportSourceDefinition&MockObject
     */
    private $importSourceDefinition;

    protected function setUp(): void
    {
        parent::setUp();
    }
}
