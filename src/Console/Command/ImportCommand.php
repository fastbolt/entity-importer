<?php

namespace Fastbolt\EntityImporter\Console\Command;

use Fastbolt\EntityImporter\Console\ImportCommandHelpRenderer;
use Fastbolt\EntityImporter\Console\ImportCommandResultRenderer;
use Fastbolt\EntityImporter\EntityImporterManager;
use Fastbolt\EntityImporter\Exceptions\ImporterDefinitionNotFoundException;
use Fastbolt\EntityImporter\Types\ImportError;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportCommand extends Command
{
    /**
     * @var EntityImporterManager
     */
    private EntityImporterManager $entityImporterManager;

    /**
     * @var ImportCommandHelpRenderer
     */
    private ImportCommandHelpRenderer $helpRenderer;

    /**
     * @var ImportCommandResultRenderer
     */
    private ImportCommandResultRenderer $resultRenderer;

    /**
     * @param EntityImporterManager $entityImporterManager
     */
    public function __construct(
        EntityImporterManager $entityImporterManager,
        ImportCommandHelpRenderer $helpRenderer,
        ImportCommandResultRenderer $resultRenderer
    ) {
        parent::__construct();

        $this->entityImporterManager = $entityImporterManager;
        $this->helpRenderer          = $helpRenderer;
        $this->resultRenderer        = $resultRenderer;
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
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io    = new SymfonyStyle($input, $output);
        $type  = $input->getArgument('type');
        $limit = $input->getOption('limit');

        try {
            $bar    = new ProgressBar($output);
            $result = $this->entityImporterManager->import(
                $type,
                static function () use ($bar) {
                    $bar->advance();

                    return true;
                },
                static function (ImportError $error) {
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
