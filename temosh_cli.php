<?php

use Temosh\Console\Command\SelectCommand;
use Temosh\Console\Shell;

$application = new Shell();
$command = new SelectCommand();

$application->add($command);

$application->setDefaultCommand($command->getName(), true);
$application->run();
