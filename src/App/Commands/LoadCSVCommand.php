<?php
namespace Console\App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LoadCSVCommand extends Command
{
    protected function configure()
    {
        $this->setName('loadcsv')
            ->setHelp('Console command to load and process csv file.')
            ->setDescription('Console command to load and process csv file. <example.csv>')
            ->addArgument('file',InputArgument::REQUIRED, 'path to csv file');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
       $output->writeln(sprintf('File is , %s', $input->getArgument('file')));
       return Command::SUCCESS;
    }
}