#!/usr/bin/env php
<?php
declare(strict_types=1);

namespace UAClassifier\Cli;

use Symfony\Component\Console\Application;

require __DIR__ . '/vendor/autoload.php';

$application = new Application();

$application->add(new CreateCommand);
$application->add(new ModifyCommand);
$application->add(new ModifyInteractiveCommand);

$application->run();
