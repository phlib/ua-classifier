#!/usr/bin/php
<?php

// Config

$outputFile = dirname(__DIR__) . '/test-cases.yaml';

$sources = [
    'uc-d' => 'https://raw.githubusercontent.com/ua-parser/uap-core/master/tests/test_device.yaml',
    'uc-o' => 'https://raw.githubusercontent.com/ua-parser/uap-core/master/tests/test_os.yaml',
    'uc-u' => 'https://raw.githubusercontent.com/ua-parser/uap-core/master/tests/test_ua.yaml'
];

// Script

require dirname(dirname(__DIR__)) . '/vendor/autoload.php';

if (is_file($outputFile) && !is_writable($outputFile)) {
    throw new \RuntimeException('Output file is not writable');
}

$testData = [];
if (is_readable($outputFile)) {
    echo "Found existing file; loading data... ";
    $testData = \Symfony\Component\Yaml\Yaml::parse($outputFile);
    echo "Done\n\n";
}

$parser = \UAParser\Parser::create();

$addEntries = function ($sourceName, $sourcePath) use (&$testData, $parser) {
    echo "Starting source '{$sourceName}'; loading data... ";
    $source = \Symfony\Component\Yaml\Yaml::parse(file_get_contents($sourcePath));
    echo "Done\n";

    echo "Iterating entries...\n\n";
    $count = 0;
    foreach ($source['test_cases'] as $test) {
        $result = $parser->parse($test['user_agent_string']);

        if ($result->ua->family === 'Other') {
//            continue;
        }

        $key = $result->device->family . '-' .
            $result->os->family . '-' .
            $result->ua->family;

        if (isset($testData[$key])) {
            continue;
        }

        $testData[$key] = [
            's' => $sourceName,
            'd' => $result->device->family,
            'o' => $result->os->family,
            'u' => $result->ua->family,
            // default value to be modified manually later
            'c' => ''
        ];
        $count++;
    }

    return $count;
};

$countAdd = 0;
foreach ($sources as $source => $url) {
    $countAdd += $addEntries($source, $url);
}
$countTotal = count($testData);

echo "\nReady to write {$countAdd} entries, totalling {$countTotal}\n\n";

echo "Press Enter to write to file, Ctrl+C to quit without saving changes\n";
fgets(STDIN);
echo "Writing file...\n\n";
file_put_contents(
    $outputFile,
    \Symfony\Component\Yaml\Yaml::dump($testData, 1)
);

$mem = round(memory_get_usage() /1024);
echo "Complete. {$mem}kb memory\n\n";
