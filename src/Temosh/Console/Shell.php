<?php

namespace Temosh\Console;

use Symfony\Component\Console\Application;

class Shell extends Application
{
    const VERSION = 'v0.0.1';
    const NAME = 'Temosh\Console';

    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        parent::__construct(self::NAME, self::VERSION);
    }

}
