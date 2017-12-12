<?php
declare(strict_types=1);

namespace UAClassifier\Cli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Yaml\Yaml;

class ModifyCommand extends Command
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
        's' => 'Spider'
    ];

    /**
     * @var array $targetMap
     */
    protected $targetMap = [
        'u' => 'User Agent',
        'o' => 'Operating System',
        'd' => 'Device'
    ];

    /**
     * @inheritdoc
     */
    protected function configure(): void
    {
        $this->setName('modify')
             ->setDescription('Modify test case data with prompted device classification')
             ->addOption('interactive', 'i', InputOption::VALUE_NONE, 'Modify test cases interactively & individually');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $outputFile = $this->getOutputFile();

        $io = new SymfonyStyle($input, $output);
        $io->title('Modify Test Cases');

        if ($input->getOption('interactive')) {
            $this->classifyInteractive($outputFile, $io);
        } else {
            $this->classify($outputFile, $io);
        }
    }

    /**
     * Classify test cases with prompted classification configuration
     *
     * @param $outputFile string
     * @param $io SymfonyStyle
     */
    protected function classify($outputFile, $io): void
    {
        $io->section('Classification Configuration');

        $classification = $io->choice('Select classification to grant', $this->classificationMap);

        $target = $io->choice('Apply classification by', $this->targetMap);

        $regex = $io->ask("Specify regex to classify records by", null, function($value) {
            if (!preg_match('/^\/.+\/[gmixXsuUAJD]?$/', $value)) {
                throw new \RuntimeException('Please enter a valid regex.');
            }
            return $value;
        });

        $overwrite = $io->confirm('Overwrite existing classifications', false);

        $io->section('Classifying Test Cases');

        $io->text("Parsing test cases from: <comment>{$outputFile}</comment>");
        $this->testCaseData = Yaml::parse(file_get_contents($outputFile));

        $totalRecords = count($this->testCaseData);
        $io->text("<info>Parsed {$totalRecords} records</info>");

        $io->text("Classifying records as <comment>{$this->classificationMap[$classification]}</comment> where"
            . " <comment>{$this->targetMap[$target]}</comment> matches <comment>{$regex}</comment>");

        $classifiedRecords = 0;
        $unclassifiedRecords = 0;
        foreach ($this->testCaseData as $key => &$testCase) {
            if (!empty($testCase['c']) && $overwrite === false) {
                continue;
            }
            if (preg_match($regex, $testCase[$target])) {
                $testCase['c'] = lcfirst($this->classificationMap[$classification]);
                $classifiedRecords++;
            }
            if (!$testCase['c']) {
                $unclassifiedRecords++;
            }
        }

        if ($classifiedRecords > 0) {
            $writeFile = $io->confirm("Classify {$classifiedRecords} of {$totalRecords} records?"
             . " ({$unclassifiedRecords} unclassified records remaining)", true);
            if ($writeFile) {
                $filesize = round(memory_get_usage() / 1024);
                $io->text('Writing file...');
                file_put_contents($outputFile, Yaml::dump($this->testCaseData, 1));
                $io->success("Successfully wrote test cases:\n{$outputFile} ({$filesize}kb)");
            } else {
                $io->warning('Writing of test cases aborted');
            }
        } else {
            $io->success('No matching records to be classified');
        }
    }

    /**
     * Classify test cases interactively and individually via Google image search
     *
     * @param $outputFile string
     * @param $io SymfonyStyle
     */
    protected function classifyInteractive($outputFile, $io): void
    {
        $io->section('Loading Existing Test Cases');

        $io->text("Parsing test cases from: <comment>{$outputFile}</comment>");
        $this->testCaseData = Yaml::parse(file_get_contents($outputFile));

        $totalRecords = count($this->testCaseData);
        $io->text("<info>Parsed {$totalRecords} records</info>");

        $io->section('Classifying Test Cases Interactively');
        $classifiedRecords = 0;

        $this->classificationMap = array_merge($this->classificationMap, [
           'u' => 'Unclassified - skip',
           'q' => 'Quit - save & close'
        ]);

        foreach ($this->testCaseData as $key => &$testCase) {
            if ($testCase['c']) {
                $io->text("Skipping record: <comment>{$key}</comment> (already classified - {$testCase['c']})");
                continue;
            }
            if ($testCase['d'] === 'Other') {
                $io->text("Skipping record: <comment>{$key}</comment> (empty device)");
                continue;
            }

            $io->text("Classifying record: <comment>{$key}</comment>");
            $googleImageSearchUrl = 'https://www.google.com/search?tbm=isch&q=' . urlencode($testCase['d']);
            exec('open ' . escapeshellarg($googleImageSearchUrl));

            $classification = $io->choice('Select classification to grant', $this->classificationMap);
            if ($classification === 'q') {
                break;
            }
            if ($classification === 'u') {
                continue;
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
                $io->success("Successfully wrote test cases:\n{$outputFile} ({$filesize}kb)");
            } else {
                $io->warning('Writing of test cases file aborted');
            }
        } else {
            $io->success('No records to be classified');
        }
    }

    /**
     * Get the test cases output file
     *
     * @return string
     * @throws \RuntimeException
     */
    protected function getOutputFile(): string
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
        return $outputFile;
    }
}
