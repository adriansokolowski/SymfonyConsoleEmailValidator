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
        $array = $fields = array(); 
        $i = 0;
        $handle = @fopen($input->getArgument('file'), "r");
        if ($handle) {
            while (($row = fgetcsv($handle, 4096)) !== false) {
                if (empty($fields)) {
                    $fields = $row;
                    continue;
                }
                foreach ($row as $k=>$value) {
                    $array[$i][$fields[$k]] = $value;
                    $output->writeln($array[$i][$fields[$k]]);
                }
                $i++;
            }
            if (!feof($handle)) {
                echo "Error: unexpected fgets() fail\n";
            }
            fclose($handle);
        }

        return Command::SUCCESS;
    }



}