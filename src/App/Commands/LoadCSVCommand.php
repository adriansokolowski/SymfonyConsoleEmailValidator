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
        $output->writeln('Loaded file: ' . $input->getArgument('file'));
        $rows = array_map('str_getcsv', file($input->getArgument('file')));
        $csv = [];
        $validEmails = [];
        $invalidEmailsSyntax = [];
        $invalidEmailsHost = [];
        foreach($rows as $row) {
            $csv[] = $row;
        }
        foreach($csv as $k => $v) {
            $v = implode('', $v);
            if (filter_var($v, FILTER_VALIDATE_EMAIL)) {
                $domain = substr($v, strpos($v, '@') + 1);
                if  (checkdnsrr($domain) !== FALSE) {
                    $validEmails[] = $v;
                } else {
                    $invalidEmailsHost[] = $v;
                }
                $validEmails[] = $v;
            } else {
                $invalidEmailsSyntax[] = $v;
                $output->writeln($v);
            }
        }
        
        return Command::SUCCESS;
    }



}