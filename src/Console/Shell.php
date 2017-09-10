<?php

namespace Temosh\Console;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\HelperInterface;
use Symfony\Component\Console\Input\InputInterface;
use Temosh\Console\Helper\QuestionHelper;
use Temosh\Mongo\Client\Client;
use Temosh\Mongo\Connection\OptionsNormalizer;
use Temosh\Mongo\Connection\OptionsValidator;
use Temosh\Mongo\Query\MongoQueryBuilder;
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
     * @var \Temosh\Mongo\Query\MongoQueryBuilder
     *  Query builder instance.
     */
    private $mongoQueryBuilder;

    /**
     * @var \Temosh\Mongo\Client\Client
     *  MongoDB client instance.
     */
    private $mongoClient;

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
        $this->mongoQueryBuilder = new MongoQueryBuilder();
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

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *  The Input instance.
     * @param bool $forceNew
     *  Flag indicates that new mongo client should be created even it already exists.
     *
     * @return \Temosh\Mongo\Client\Client
     */
    public function getMongoClientFromInput(InputInterface $input, $forceNew = false)
    {
        if ($this->mongoClient === null || ((bool) $forceNew)) {
            $this->mongoClient = Client::fromUserInput($input);
        }

        return $this->mongoClient->setQueryBuilder($this->mongoQueryBuilder);
    }
}
