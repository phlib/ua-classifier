<?php
declare(strict_types=1);

namespace UAClassifier\Cli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Yaml\Yaml;
use UAParser\Parser;

class CreateCommand extends Command
{
    /**
     * @var array $sources ua-parser/uap-core test-case data sources
     */
    protected $sources = [
        [
            'name' => 'device',
            'key'  => 'uc-d',
            'url'  => 'https://raw.githubusercontent.com/ua-parser/uap-core/master/tests/test_device.yaml'
        ],
        [
            'name' => 'operating system',
            'key'  => 'uc-o',
            'url'  => 'https://raw.githubusercontent.com/ua-parser/uap-core/master/tests/test_os.yaml'
        ],
        [
            'name' => 'user agent',
            'key'  => 'uc-u',
            'url'  => 'https://raw.githubusercontent.com/ua-parser/uap-core/master/tests/test_ua.yaml'
        ]
    ];

    /**
     * @var array $testCaseData Array to append test case data to before parsing to YAML
     */
    protected $testCaseData = [];

    /**
     * @inheritdoc
     */
    protected function configure(): void
    {
        $this->setName('create')
             ->setDescription('Create test-case data for unit tests: `tests/test-cases.yaml`');
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

        $io->section('Fetching New Test Cases');

        $addedRecords = 0;
        foreach ($this->sources as $source) {
            $addedRecords += $this->addRecords($source, $io);
        }

        if ($addedRecords > 0) {
            $writeFile = $io->confirm("Write {$addedRecords} new records, totalling {$totalRecords}?", true);
            if ($writeFile) {
                $filesize = round(memory_get_usage() / 1024);
                $io->text('Writing file...');
                file_put_contents($outputFile, Yaml::dump($this->testCaseData, 1));
                $io->success("Successfully wrote test-case file:\n{$outputFile} ({$filesize}kb)");
            } else {
                $io->warning('Writing of test-case file aborted');
            }
        } else {
            $io->success('No additional records to be written');
        }
    }

    /**
     * Fetch & add new records to test case data
     *
     * @param $source array
     * @param $io SymfonyStyle
     * @return int The number of records added
     * @throws \UAParser\Exception\FileNotFoundException
     */
    protected function addRecords($source, $io): int
    {
        $io->section(ucfirst("{$source['name']}s"));

        $io->text("Fetching {$source['name']}s from: <comment>{$source['url']}</comment>");
        $data = Yaml::parse(file_get_contents($source['url']));
        $records = count($data['test_cases']);
        $io->text("<info>Fetched {$records} {$source['name']}s</info>");

        $io->text("Parsing {$source['name']}s with UAParser...");
        $parser = Parser::create();
        $addedRecords = 0;
        foreach ($data['test_cases'] as $testCase) {
            $result = $parser->parse($testCase['user_agent_string']);
            $key = "{$result->device->family}-{$result->os->family}-{$result->ua->family}";

            if (isset($this->testCaseData[$key])) { continue; }

            $this->testCaseData[$key] = [
                's' => $source['key'],
                'd' => $result->device->family,
                'o' => $result->os->family,
                'u' => $result->ua->family,
                'c' => '' // Class to be added via classify-test-cases command
            ];
            $addedRecords++;
        }
        $io->text(["<info>Added {$addedRecords} new {$source['name']}s</info>"]);
        return $addedRecords;
    }
}
