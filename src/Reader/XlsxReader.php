<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Reader;

use PhpOffice\PhpSpreadsheet\IOFactory;
use SplFileObject;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class XlsxReader implements ReaderInterface
{
    /**
     * Faulty rows
     *
     * @var array<int,array<int,mixed>>|null
     */
    protected ?array $errors = null;

    /**
     * @var array
     */
    protected array $columnHeaders;

    /**
     * Total number of rows
     *
     * @var int
     */
    protected int $count;

    /**
     * @var int|null
     */
    protected ?int $headerRowNumber = null;

    /**
     * @var int
     */
    protected int $pointer = 0;

    /**
     * @var array
     */
    protected array $worksheet;

    /**
     * @param SplFileObject     $file
     * @param array<int,string> $columnHeaders
     * @param ?int              $headerRowNumber
     */
    public function __construct(SplFileObject $file, array $columnHeaders, ?int $headerRowNumber)
    {
        $reader = IOFactory::createReaderForFile($file->getPathName());
        $reader->setReadDataOnly(true);

        $spreadsheet = $reader->load($file->getPathname());

        $this->worksheet = $spreadsheet->getActiveSheet()->toArray();

        if (null !== $headerRowNumber) {
            $this->setHeaderRowNumber($headerRowNumber);
        }
        $this->setColumnHeaders($columnHeaders);
    }

    public function setColumnHeaders(array $columnHeaders): void
    {
        $this->columnHeaders = $columnHeaders;
    }

    /**
     * @inheritDoc
     */
    public function getErrors(): array
    {
        if (null === $this->errors) {
            $this->errors = [];
            /**
             * @var int              $index
             * @var array<int,mixed> $line
             */
            foreach ($this->worksheet as $index => $line) {
                if (count($this->columnHeaders) === count($line)) {
                    continue;
                }

                $this->errors[$index] = $line;
            }
        }

        /** @psalm-var array<int,array<int,mixed>> */
        return $this->errors;
    }

    public function current()
    {
        $row = $this->worksheet[$this->pointer];

        // If the spreadsheet file has column headers, use them to construct an associative
        // array for the columns in this line
        if (!empty($this->columnHeaders) && count($this->columnHeaders) === count($row)) {
            return array_combine(array_values($this->columnHeaders), $row);
        }

        // Else just return the column values
        return $row;
    }

    public function setHeaderRowNumber(int $rowNumber): void
    {
        $this->headerRowNumber = $rowNumber;
        $this->columnHeaders   = $this->worksheet[$rowNumber];
    }

    public function getColumnHeaders(): array
    {
        return $this->columnHeaders;
    }

    public function next(): void
    {
        $this->pointer++;
    }

    public function key(): mixed
    {
        return $this->pointer;
    }

    public function valid(): bool
    {
        return isset($this->worksheet[$this->pointer]);
    }

    public function rewind(): void
    {
        if (null === $this->headerRowNumber) {
            $this->pointer = 0;
        } else {
            $this->pointer = $this->headerRowNumber + 1;
        }
    }
}
