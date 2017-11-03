#!/usr/bin/env php
<?php

require dirname(__DIR__).'/vendor/autoload.php';

use App\Command\GetCompetitionsCommand;
use Symfony\Component\Console\Application;

$application = new Application('CompetRunning', '1.0.0');
$command = new GetCompetitionsCommand();

$application->add($command);

$application->setDefaultCommand($command->getName(), true);
$application->run();
