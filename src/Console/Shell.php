<?php

namespace Temosh\Console;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\HelperInterface;
use Temosh\Console\Helper\QuestionHelper;
use Temosh\Mongo\Connection\OptionsNormalizer;
use Temosh\Mongo\Connection\OptionsValidator;

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


        // Add required helper instance for application.
        $helper = new QuestionHelper(
            new OptionsNormalizer(),
            new OptionsValidator()
        );

        $this->setHelper($helper);
    }

    /**
     * Set helper to the application.
     *
     * @param \Symfony\Component\Console\Helper\HelperInterface $helper
     */
    public function setHelper(HelperInterface $helper)
    {
        $this->getHelperSet()->set($helper);
    }

}
