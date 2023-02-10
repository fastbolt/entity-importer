<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Console;

use Fastbolt\EntityImporter\EntityImporterManager;
use Symfony\Component\Console\Style\StyleInterface;

class ImportCommandHelpRenderer
{
    /**
     * @var EntityImporterManager
     */
    private EntityImporterManager $entityImporterManager;

    /**
     * @param EntityImporterManager $entityImporterManager
     */
    public function __construct(EntityImporterManager $entityImporterManager)
    {
        $this->entityImporterManager = $entityImporterManager;
    }

    /**
     * @param StyleInterface $io
     *
     * @return void
     */
    public function render(StyleInterface $io): void
    {
        $io->title('Available import types');
        $importers = $this->entityImporterManager->getImporterDefinitions();
        if (0 === count($importers)) {
            $io->error('No import types configured.');

            return;
        }
        $table = $io->createTable()
                    ->setHeaders(['Name', 'Description', 'Target Entity', 'Input Source']);

        foreach ($importers as $name => $importer) {
            $sourceDefinition = $importer->getImportSourceDefinition();
            $table->addRow(
                [
                    $name,
                    $importer->getDescription(),
                    $importer->getEntityClass(),
                    $sourceDefinition->getSource(),
                ]
            );
        }
        $table->render();
    }
}
