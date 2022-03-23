<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Console;

use Fastbolt\EntityImporter\Types\ImportError;
use Fastbolt\EntityImporter\Types\ImportResult;
use Symfony\Component\Console\Style\StyleInterface;

class ImportCommandResultRenderer
{
    private const MAX_SHOW_ERRORS = 1000;

    /**
     * @param StyleInterface $io
     *
     * @return void
     */
    public function render(StyleInterface $io, ImportResult $importResult): void
    {
        $errors     = $importResult->getErrors();
        $numErrors  = count($errors);
        $numSuccess = $importResult->getSuccess();

        if ($numSuccess <= 0 && $numErrors <= 0) {
            $io->warning('Did not find anything to import, but did not encounter any error though.');

            return;
        }

        if ($numErrors <= 0) {
            $io->success(sprintf('Successfully imported %s items', $importResult->getSuccess()));

            return;
        }

        $count = 0;
        $io->error(
            sprintf(
                '%s entries were imported successfully, %s errors occured%s.',
                $numSuccess,
                $numErrors,
                $numErrors > self::MAX_SHOW_ERRORS
                    ? sprintf(' (only first %s are displayed)', self::MAX_SHOW_ERRORS)
                    : ''
            )
        );
        $io->table(
            ['Line', 'Error Message'],
            array_filter(
                array_map(
                    static function (ImportError $error) use (&$count): ?array {
                        if ($count >= self::MAX_SHOW_ERRORS) {
                            return null;
                        }
                        $count++;

                        return [$error->getLine(), $error->getMessage()];
                    },
                    $errors
                )
            )
        );
    }
}
