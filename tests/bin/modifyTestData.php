#!/usr/bin/php
<?php

// Config

$outputFile = dirname(__DIR__) . '/test-cases.yaml';

$config = [
    'device' => null, // regex, or null to skip; eg. /^iPad$/i
    'os'     => null, // regex, or null to skip
    'ua'     => null, // regex, or null to skip
    'class'  => 'desktop',  // new class: desktop, tablet, mobile, spider
    'test'   => true,      // display matching entries without modifying
    'skipNonEmpty' => true // skip entries where class is already defined
];

// Script

require dirname(dirname(__DIR__)) . '/vendor/autoload.php';

if ($config['device'] === null && $config['os'] === null && $config['ua'] === null) {
    throw new \RuntimeException('At least one match must be specified');
}

$testData = \Symfony\Component\Yaml\Yaml::parse($outputFile);

$countTotal = 0;
$countEmpty = 0;
$countWritten = 0;
foreach ($testData as $key => &$testCase) {
    $countTotal++;
    if (empty($testCase['class'])) {
        $countEmpty++;
    } elseif ($config['skipNonEmpty'] !== false) {
        continue;
    }

    if ($config['device'] !== null && preg_match($config['device'], $testCase['device']) === 0) {
        continue;
    }
    if ($config['os'] !== null && preg_match($config['os'], $testCase['os']) === 0) {
        continue;
    }
    if ($config['ua'] !== null && preg_match($config['ua'], $testCase['ua']) === 0) {
        continue;
    }

    // We're going to update this one
    $countWritten++;
    if (empty($testCase['class'])) {
        $countEmpty--;
    }

    echo "Write '{$config['class']}' to key '{$key}' \n";

    $testCase['class'] = $config['class'];
}

echo "\n";

if ($config['test'] === false && $countWritten > 0) {
    echo "Writing file...\n\n";
    file_put_contents(
        $outputFile,
        \Symfony\Component\Yaml\Yaml::dump($testData, 1)
    );
}

$mem = round(memory_get_usage() /1024);
if ($config['test'] !== false) {
    echo "Testing. No data changed\n";
}
echo "Complete. Written {$countWritten} entries, totalling {$countTotal}, leaving {$countEmpty} empty, using {$mem}kb memory\n\n";
