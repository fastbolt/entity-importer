<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Reader\Reader;

use Port\Csv\CsvReader as PortCsvReader;
use SplFileObject;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class CsvReader extends PortCsvReader implements ReaderInterface
{
    /**
     * @param SplFileObject     $file
     * @param array<int,string> $columnHeaders
     * @param ?int              $headerRowNumber
     * @param string            $delimiter
     * @param string            $enclosure
     * @param string            $escape
     */
    public function __construct(
        SplFileObject $file,
        array $columnHeaders,
        ?int $headerRowNumber = null,
        string $delimiter = ',',
        string $enclosure = '"',
        string $escape = '\\'
    ) {
        parent::__construct(
            $file,
            $delimiter,
            $enclosure,
            $escape
        );
        if (null !== $headerRowNumber) {
            $this->setHeaderRowNumber($headerRowNumber);
        }
        $this->setColumnHeaders($columnHeaders);
    }

    /**
     * Method only added to implement method with return type.
     *
     * @noinspection PhpRedundantMethodOverrideInspection
     * @return array<int,array<int,mixed>>
     */
    public function getErrors(): array
    {
        /** @psalm-var array<int,array<int,mixed>> */
        return parent::getErrors();
    }
}
