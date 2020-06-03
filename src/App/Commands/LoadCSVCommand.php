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
        $invalidEmails = [];
        $invalidEmailsSyntax = [];
        $invalidEmailsHost = [];
        foreach($rows as $row) {
            $csv[] = $row;
        }
        foreach($csv as $k => $v) {
            $v = implode('', $v);
            if ($this->isInvalidSyntax($v)) {
                $invalidEmailsSyntax[] = $v;
                $invalidEmails[] = $v;
                continue;
            }
            if ($this->isInvalidHost($v)) {
                $invalidEmailsHost[] = $v;
                $invalidEmails[] = $v;
                continue;
            }
            $validEmails[] = $v;
        }
        $this->saveCSV($validEmails, 'validemails.csv');
        $this->saveCSV($invalidEmails, 'invalidemails.csv');

        $summaryFile = fopen("summary.txt", "w");
        fwrite($summaryFile, "Total emails checked: " . count($csv) . "\n");
        fwrite($summaryFile, "Total valid emails: " . count($validEmails) . "\n");
        fwrite($summaryFile, "Total invalid emails: " . count($invalidEmails) . "\n");
        fwrite($summaryFile, "Emails with invalid syntax: " . count($invalidEmailsSyntax) . "\n");
        fwrite($summaryFile, "Emails with invalid email provider: " . count($invalidEmailsHost) . "\n");
        fclose($summaryFile);

        return Command::SUCCESS;
    }

    public function isInvalidSyntax($email){
        return (filter_var($email, FILTER_VALIDATE_EMAIL) === FALSE);
    }

    public function isInvalidHost($email){
        $domain = substr($email, strpos($email, '@') + 1);
        return (checkdnsrr($domain) === FALSE);
    }

    public function saveCSV($data, $file){
        $fp = fopen($file, 'w');
        foreach ($data as $row) {
            fputcsv($fp, (array) $row);
        }
        fclose($fp);
    }
}