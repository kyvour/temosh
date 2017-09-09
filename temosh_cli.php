<?php

use Temosh\Console\Command\ReadCommand;
use Temosh\Console\Shell;

$application = new Shell();

$command = new ReadCommand();

$application->add($command);

$application->setDefaultCommand($command->getName(), true);
$application->run();
