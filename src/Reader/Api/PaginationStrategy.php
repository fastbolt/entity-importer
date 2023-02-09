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
}
