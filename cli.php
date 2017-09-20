<?php

declare(strict_types=1);

use Temosh\Console\Command\ReadCommand;
use Temosh\Console\Shell;

$application = new Shell();
$command = $application->add(new ReadCommand());

$application->setDefaultCommand($command->getName(), true)->run();
