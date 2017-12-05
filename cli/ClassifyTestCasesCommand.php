<?php

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Yaml\Yaml;

class ClassifyTestCasesCommand extends Command
{
    /**
     * @var array $testCaseData Array to append test case data to before parsing to YAML
     */
    protected $testCaseData = [];

    /**
     * @inheritdoc
     */
    protected function configure(): void
    {
        $this->setName('classify-test-cases')
             ->setDescription('Add device classification to the test case file `tests/test-cases.yaml`');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Classification Configuration');

        $classification = $io->choice('Select the classification to grant', ['Mobile', 'Tablet', 'Computer', 'Spider']);
        $target = $io->choice('Select what to classify by', ['User Agent', 'Operating System', 'Device']);
        $regex = $io->ask("Specify a regex to classify {$target}s with the {$classification} classification", null, function($value) {
            if (!preg_match('/^\/[\s\S]+\/$/', $value)) {
                throw new \RuntimeException('Please enter a valid regex.');
            }
            return $value;
        });
        $overwrite = $io->confirm('Overwrite existing classifications?', false);

        $overwriteMessage = $overwrite ? '(overwriting)' : '(non-overwriting)';
        $continue = $io->confirm("Classify all {$overwriteMessage} {$target}s that match {$regex} with the {$classification} classification?");
        if ($continue) {
            $this->classify($classification, $target, $regex, $overwrite, $io);
        } else {
            $io->warning('Classification aborted');
        }
    }

    /**
     * Classify test-cases with the specified classification which target matches the regex.
     *
     * @param $classification string
     * @param $target string
     * @param $regex string
     * @param $overwrite bool
     * @param $io SymfonyStyle
     */
    protected function classify($classification, $target, $regex, $overwrite, $io): void
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

        $io->title('Loading Existing Test Cases');

        $io->text("Parsing test cases from: <comment>{$outputFile}</comment>");
        $this->testCaseData = Yaml::parse(file_get_contents($outputFile));
        $totalRecords = count($this->testCaseData);
        $io->text('<info>Parsed ' . count($this->testCaseData) . ' records</info>');

        $io->title('Classifying Test Cases');

        $io->text("Classifying <comment>${totalRecords}</comment> records which <comment>{$target}</comment> matches <comment>{$regex}</comment>...");

        $classifiedRecords = 0;
        $unclassifiedRecords = 0;
        foreach ($this->testCaseData as $key => &$testCase) {
            if (!$testCase['c']) {
                $unclassifiedRecords++;
            }
            if ($testCase['c'] && !$overwrite) {
                continue;
            }
            $target = lcfirst(substr($target, 0, 1));
            if (!preg_match($regex, $testCase[$target])) {
                continue;
            }
            $testCase['c'] = lcfirst($classification);
            $classifiedRecords++;
        }

        if ($classifiedRecords > 0) {
            $writeFile = $io->confirm("Ready to modify {$classifiedRecords} records, totalling {$totalRecords}? ({$unclassifiedRecords} unclassified records remaining)", true);
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
