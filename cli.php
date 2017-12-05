#!/usr/bin/env php
<?php

use Symfony\Component\Console\Application;

require __DIR__ . '/vendor/autoload.php';

$application = new Application();

$application->add(new CreateTestCasesCommand);
$application->add(new ClassifyTestCasesCommand);

$application->run();
