#!/usr/bin/php
<?php

// Config

$outputFile = dirname(__DIR__) . '/test-cases.yaml';

$sources = [
    'uap-core-device' => 'https://raw.githubusercontent.com/ua-parser/uap-core/master/tests/test_device.yaml',
    'uap-core-os'     => 'https://raw.githubusercontent.com/ua-parser/uap-core/master/tests/test_os.yaml',
    'uap-core-ua'     => 'https://raw.githubusercontent.com/ua-parser/uap-core/master/tests/test_ua.yaml'
];

// Script

require dirname(dirname(__DIR__)) . '/vendor/autoload.php';

if (is_file($outputFile) && !is_writable($outputFile)) {
    throw new \RuntimeException('Output file is not writable');
}

$testData = [];
if (is_readable($outputFile)) {
    $testData = \Symfony\Component\Yaml\Yaml::parse($outputFile);
}

$parser = \UAParser\Parser::create();

$addEntries = function ($sourceName, $sourcePath) use (&$testData, $parser) {
    $source = \Symfony\Component\Yaml\Yaml::parse(file_get_contents($sourcePath));
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
            'source' => $sourceName,
            'device' => $result->device->family,
            'os'     => $result->os->family,
            'ua'     => $result->ua->family,
            // default value to be modified manually later
            'class'  => ''
        ];
        $count++;
    }

    return $count;
};

$countAdd = 0;
foreach ($sources as $source => $url) {
    $countAdd += $addEntries($source, $url);
}

file_put_contents(
    $outputFile,
    \Symfony\Component\Yaml\Yaml::dump($testData, 1)
);

$countTotal = count($testData);
$mem = round(memory_get_usage() /1024);
echo "Complete. Written {$countAdd} entries, totalling {$countTotal}, using {$mem}kb memory\n\n";
