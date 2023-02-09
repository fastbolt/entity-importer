<?php

namespace Fastbolt\EntityImporter\Reader\Api;

interface PaginationStrategy
{
    /**
     * Return guzzle compatible request parameter array.
     * Examples:
     *      ['query' => ['page' => 1]]
     *      ['query' => ['offset' => 0, 'limit' => 100]]
     *
     * @return array<string,mixed>
     */
    public function getRequestParameters(int $offset): array;

    /**
     * Return the number of items per page.
     *
     * @return int
     */
    public function getItemsPerPage(): int;

    /**
     * Return page start offset (0-based) for item.
     *
     * Example: Page size 100, item offset = 101 => will return 100, because item 101 is on page 2 which
     * starts at offset 100.
     *
     * @return int
     */
    public function getPageStartOffset(int $itemOffset): int;
}
