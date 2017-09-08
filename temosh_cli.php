<?php

// Require BootLoader class because composer's autoloader isn't available yet.
require __DIR__ . '/boot/BootLoader.php';
\Temosh\Boot\BootLoader::boot(__DIR__);

use Symfony\Component\Console\Application;

$application = new Application();

// ... register commands

$application->run();
