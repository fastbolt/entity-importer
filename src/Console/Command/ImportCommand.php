<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\Console\Command;

use Fastbolt\EntityImporter\Console\ImportCommandHelpRenderer;
use Fastbolt\EntityImporter\Console\ImportCommandResultRenderer;
use Fastbolt\EntityImporter\EntityImporterManager;
use Fastbolt\EntityImporter\Exceptions\ImporterDefinitionNotFoundException;
use Fastbolt\EntityImporter\Exceptions\InvalidInputFormatException;
use Fastbolt\EntityImporter\Exceptions\SourceUnavailableException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

class ImportCommand extends Command
{
    /**
     * @param EntityImporterManager       $entityImporterManager
     * @param ImportCommandHelpRenderer   $helpRenderer
     * @param ImportCommandResultRenderer $resultRenderer
     */
    public function __construct(
        private readonly EntityImporterManager $entityImporterManager,
        private readonly ImportCommandHelpRenderer $helpRenderer,
        private readonly ImportCommandResultRenderer $resultRenderer
    ) {
        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('entity_importer:import')
             ->setDescription('Generic entity importer command')
             ->addArgument('type', InputArgument::OPTIONAL, 'Type to import', '')
             ->addOption('limit', 'l', InputOption::VALUE_OPTIONAL, 'Limit imported lines', null);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     * @throws InvalidInputFormatException
     * @throws SourceUnavailableException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io    = new SymfonyStyle($input, $output);
        $type  = $input->getArgument('type');
        $limit = $input->getOption('limit');

        try {
            $bar = new ProgressBar($output);
            $bar->setRedrawFrequency(100);
            $result = $this->entityImporterManager->import(
                $type,
                static function () use ($bar) {
                    $bar->advance();

                    return true;
                },
                static function (Throwable $error) {
                    return true;
                },
                $limit
            );
            $bar->finish();
            $this->resultRenderer->render($io, $result);
        } catch (ImporterDefinitionNotFoundException $exception) {
            $io->newLine(2);
            if ($exception->getName() !== '') {
                $io->error($exception->getMessage());
            }

            $this->helpRenderer->render($io);

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
