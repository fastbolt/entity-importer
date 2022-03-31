<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Reader\Reader;

use Port\Spreadsheet\SpreadsheetReader;
use SplFileObject;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class XlsxReader extends SpreadsheetReader implements ReaderInterface
{
    public const TYPE = 'xlsx';

    /**
     * Faulty rows
     *
     * @var array<int,array>|null
     */
    protected ?array $errors = null;

    /**
     * @param SplFileObject     $file
     * @param array<int,string> $columnHeaders
     * @param ?int              $headerRowNumber
     */
    public function __construct(SplFileObject $file, array $columnHeaders, ?int $headerRowNumber)
    {
        parent::__construct($file, $headerRowNumber);

        if (null !== $headerRowNumber) {
            $this->setHeaderRowNumber($headerRowNumber);
        }
        $this->setColumnHeaders($columnHeaders);
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
}
