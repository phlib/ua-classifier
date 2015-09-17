#!/usr/bin/php
<?php

// Config

$outputFile = dirname(__DIR__) . '/test-cases.yaml';

$config = [
    'device' => null, // regex, or null to skip; eg. /^iPad$/i
    'os'     => null, // regex, or null to skip
    'ua'     => null, // regex, or null to skip
    'class'  => 'desktop',  // new class: desktop, tablet, mobile, spider
    'skipNonEmpty' => true // skip entries where class is already defined
];

// Script

require dirname(dirname(__DIR__)) . '/vendor/autoload.php';

if ($config['device'] === null && $config['os'] === null && $config['ua'] === null) {
    throw new \RuntimeException('At least one match must be specified');
}

echo "Loading data...\n";
$testData = \Symfony\Component\Yaml\Yaml::parse($outputFile);
echo "Iterating entries...\n\n";

$countTotal = 0;
$countEmpty = 0;
$countModify = 0;
foreach ($testData as $key => &$testCase) {
    $countTotal++;
    if (empty($testCase['c'])) {
        $countEmpty++;
    } elseif ($config['skipNonEmpty'] !== false) {
        continue;
    }

    if ($config['device'] !== null && preg_match($config['device'], $testCase['d']) === 0) {
        continue;
    }
    if ($config['os'] !== null && preg_match($config['os'], $testCase['o']) === 0) {
        continue;
    }
    if ($config['ua'] !== null && preg_match($config['ua'], $testCase['u']) === 0) {
        continue;
    }

    // We're going to update this one
    $countModify++;
    if (empty($testCase['c'])) {
        $countEmpty--;
    }

    echo "Write '{$config['class']}' to key '{$key}' \n";

    $testCase['c'] = $config['class'];
}

echo "\nReady to write {$countModify} entries of {$countTotal} total, leaving {$countEmpty} empty\n\n";

if ($countModify > 0) {
    echo "Press Enter to write to file, Ctrl+C to quit without saving changes\n";
    fgets(STDIN);
    echo "Writing file...\n\n";
    file_put_contents(
        $outputFile,
        \Symfony\Component\Yaml\Yaml::dump($testData, 1)
    );
}

$mem = round(memory_get_usage() /1024);
echo "Complete. {$mem}kb memory\n\n";
