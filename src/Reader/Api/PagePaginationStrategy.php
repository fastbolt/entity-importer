<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Reader\Api;

class PagePaginationStrategy implements PaginationStrategy
{
    private int $itemsPerPage;

    /**
     * @param int $itemsPerPage
     */
    public function __construct(int $itemsPerPage)
    {
        $this->itemsPerPage = $itemsPerPage;
    }

    /**
     * Return guzzle compatible request parameter array.
     *
     * @return array<string,mixed>
     */
    public function getRequestParameters(int $offset): array
    {
        return [
            'query' => [
                'page' => (int)floor($offset / $this->itemsPerPage) + 1,
            ],
        ];
    }

    /**
     * @return int
     */
    public function getItemsPerPage(): int
    {
        return $this->itemsPerPage;
    }

    /**
     * Return page start offset (0-based) for item offset (1-based).
     *
     * Example: Page size 100, item offset = 101 => will return 100, because item 101 is on page 2 which
     * starts at offset 100.
     *
     * @return int
     */
    public function getPageStartOffset(int $itemOffset): int
    {
        return (int)floor($itemOffset / $this->itemsPerPage) * $this->itemsPerPage;
    }
}
