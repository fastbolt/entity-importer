<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Tests\Unit\_Fixtures\Factory\EntityInstantiator;

use DateTime;

class TestEntityWithConstructorWithMandatoryArguments
{
    /**
     * @var DateTime
     */
    private DateTime $x;

    public function __construct(DateTime $dateTime)
    {
        $this->x = $dateTime;
    }

    /**
     * @return DateTime
     */
    public function getX(): DateTime
    {
        return $this->x;
    }
}
