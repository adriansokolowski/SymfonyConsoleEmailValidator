#!/usr/bin/env php

<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Console\Application;

$app = new Application();
$app->addCommands([new \Console\App\Commands\LoadCSVCommand()]);
$app->run();