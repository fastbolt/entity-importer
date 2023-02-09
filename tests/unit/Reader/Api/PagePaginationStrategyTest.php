<?php

namespace Fastbolt\EntityImporter\Tests\Unit\Reader\Api;

use Fastbolt\EntityImporter\Reader\Api\PagePaginationStrategy;
use Fastbolt\TestHelpers\BaseTestCase;

/**
 * @covers \Fastbolt\EntityImporter\Reader\Api\PagePaginationStrategy
 */
class PagePaginationStrategyTest extends BaseTestCase
{
    public function testPagePaginationStrategy()
    {
        $strategy = new PagePaginationStrategy(500);
        self::assertSame(500, $strategy->getItemsPerPage());

        $page = $strategy->getRequestParameters(0);
        self::assertSame(1, $page['query']['page']);

        $page = $strategy->getRequestParameters(500);
        self::assertSame(2, $page['query']['page']);

        $page = $strategy->getRequestParameters(1234);
        self::assertSame(3, $page['query']['page']);

        self::assertSame(0, $strategy->getPageStartOffset(0));
        self::assertSame(0, $strategy->getPageStartOffset(50));
        self::assertSame(0, $strategy->getPageStartOffset(499));
        self::assertSame(500, $strategy->getPageStartOffset(500));
        self::assertSame(500, $strategy->getPageStartOffset(550));
        self::assertSame(500, $strategy->getPageStartOffset(999));
        self::assertSame(1000, $strategy->getPageStartOffset(1000));
    }
}
