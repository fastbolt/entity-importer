<?php

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
}
