<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Tests\Unit\Factory;

use Fastbolt\EntityImporter\Factory\EntityInstantiator;
use Fastbolt\EntityImporter\Factory\EntityUpdater;
use Fastbolt\TestHelpers\BaseTestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Fastbolt\EntityImporter\Factory\ArrayToEntityFactory
 */
class ArrayToEntityFactoryTest extends BaseTestCase
{
    /**
     * @var EntityInstantiator&MockObject
     */
    private $entityInstantiator;

    /**
     * @var EntityUpdater&MockObject
     */
    private $entityUpdater;

    /**
     * @var callable&MockObject
     */
    private $customEntityInstantiator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->entityInstantiator       = $this->getMock(EntityInstantiator::class);
        $this->entityUpdater            = $this->getMock(EntityUpdater::class);
        $this->customEntityInstantiator = $this->getCallable();
    }
}
