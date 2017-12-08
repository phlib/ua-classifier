<?php
declare(strict_types=1);

namespace UAClassifier\Cli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Yaml\Yaml;

class ModifyInteractiveCommand extends Command
{
    /**
     * @var array $testCaseData Array to append test case data to before parsing to YAML
     */
    protected $testCaseData = [];

    /**
     * @var array $classificationMap
     */
    protected $classificationMap = [
        'm' => 'Mobile',
        't' => 'Tablet',
        'd' => 'Desktop',
        's' => 'Spider',
        'e' => 'Exit - save & close'
    ];

    /**
     * @inheritdoc
     */
    protected function configure(): void
    {
        $this->setName('modify-interactive')
             ->setDescription('Modify test-case data with prompted device classification interactively & individually');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $outputFile = dirname(__DIR__) . '/tests/test-cases.yaml';
        if (!is_file($outputFile)) {
            throw new \RuntimeException("Output file does not exist: {$outputFile}");
        }
        if (!is_writable($outputFile)) {
            throw new \RuntimeException("Output file is not writable: {$outputFile}");
        }
        if (!is_readable($outputFile)) {
            throw new \RuntimeException("Output file is not readable: {$outputFile}");
        }

        $io = new SymfonyStyle($input, $output);
        $io->section('Loading Existing Test Cases');

        $io->text("Parsing test cases from: <comment>{$outputFile}</comment>");
        $this->testCaseData = Yaml::parse(file_get_contents($outputFile));

        $totalRecords = count($this->testCaseData);
        $io->text("<info>Parsed {$totalRecords} records</info>");

        $io->section('Classifying Test Cases Interactively');

        $classifiedRecords = 0;
        foreach ($this->testCaseData as $index => &$testCase) {
            if ($testCase['c']) {
                $io->text("Skipping record: <comment>{$index}</comment> (already classified)");
                continue;
            }
            if ($testCase['d'] === 'Other') {
                $io->text("Skipping record: <comment>{$index}</comment> (empty device)");
                continue;
            }

            $io->text("Classifying record: <comment>{$index}</comment>");
            $googleImageSearchUrl = 'https://www.google.com/search?tbm=isch&q=' . urlencode($testCase['d']);
            exec('open ' . escapeshellarg($googleImageSearchUrl));

            $classification = $io->choice('Select classification to grant', $this->classificationMap);
            if ($classification === 'e') {
                break;
            }
            $testCase['c'] = lcfirst($this->classificationMap[$classification]);
            $classifiedRecords++;
        }

        if ($classifiedRecords > 0) {
            $writeFile = $io->confirm("Classify {$classifiedRecords} of {$totalRecords} records?", true);
            if ($writeFile) {
                $filesize = round(memory_get_usage() / 1024);
                $io->text('Writing file...');
                file_put_contents($outputFile, Yaml::dump($this->testCaseData, 1));
                $io->success("Successfully wrote test-case file:\n{$outputFile} ({$filesize}kb)");
            } else {
                $io->warning('Writing of test-case file aborted');
            }
        } else {
            $io->success('No records to be classified');
        }
    }
}
