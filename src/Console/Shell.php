<?php

namespace Temosh\Console;

use Symfony\Component\Console\Application;

/**
 * Class Shell. Main application class for Temosh CLI.
 */
class Shell extends Application
{
    const VERSION = 'v0.0.1';
    const NAME = 'Temosh';

    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        parent::__construct(self::NAME, self::VERSION);
    }

}
