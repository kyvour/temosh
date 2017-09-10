<?php

namespace Temosh\Console;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\HelperInterface;
use Temosh\Console\Helper\QuestionHelper;
use Temosh\Mongo\Connection\OptionsNormalizer;
use Temosh\Mongo\Connection\OptionsValidator;
use Temosh\Sql\Normalizer\Normalizer;
use Temosh\Sql\Query\Query;

/**
 * Class Shell. Main application class for Temosh CLI.
 */
class Shell extends Application implements MongoShellInterface
{

    const VERSION = 'v0.0.1';

    const NAME = 'Temosh';

    /**
     * @var \Temosh\Sql\Query\QueryInterface
     *  Sql string parser instance.
     */
    private $sqlQuery;

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

        // This app doesn't have service container, so just create required objects here.
        $normalizer = new Normalizer();
        $this->sqlQuery = new Query($normalizer);
    }

    /**
     * {@inheritdoc}
     */
    public function setHelper(HelperInterface $helper)
    {
        $this->getHelperSet()->set($helper);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSqlQuery()
    {
        return $this->sqlQuery;
    }
}
