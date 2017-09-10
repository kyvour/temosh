<?php

namespace Temosh\Mongo\Client;

use MongoDB\Driver\Command;
use MongoDB\Driver\Query;
use MongoDB\Driver\ReadPreference;
use MongoDB\Exception\InvalidArgumentException;
use MongoDB\Exception\UnexpectedValueException;
use MongoDB\Model\CollectionInfoIterator;
use PhpMyAdmin\SqlParser\Statement;
use Symfony\Component\Console\Input\InputInterface;
use Temosh\Mongo\Connection\ConnectionOptions;
use Temosh\Mongo\Query\MongoQueryBuilder;

class Client extends \MongoDB\Client implements ExtendedClientInterface
{

    /**
     * An array with default config for MongoDB connection
     */
    const DEFAULT_CONFIG = [
        'host' => ConnectionOptions::DEFAULT_HOST,
        'port' => ConnectionOptions::DEFAULT_PORT,
        'user' => '',
        'pass' => '',
        'db' => '',
        'authenticationDatabase' => '',
    ];

    /**
     * @var string
     *  The name of connected database.
     */
    protected $dbName;

    /**
     * @var \Temosh\Mongo\Query\MongoQueryBuilder
     *  Query builder instance.
     */
    protected $builder;

    /**
     * {@inheritdoc}
     */
    public static function fromArray(array $config, array $uriOptions = [], array $driverOptions = [])
    {
        // Merge entering config with the default one.
        $config = array_intersect_key(array_merge(static::DEFAULT_CONFIG, $config), static::DEFAULT_CONFIG);

        if (empty($config['db'])) {
            throw new InvalidArgumentException("The configuration doesn't have 'db' option or argument, or it's empty");
        }

        /**
         * Build connection string.
         *
         * @see http://docs.mongodb.org/manual/reference/connection-string/
         * @see http://php.net/manual/en/mongodb-driver-manager.construct.php
         */
        $connectionString = 'mongodb://' . $config['host'] . ':' . $config['port'] . '/' . $config['db'];

        // Add username to the array with uri options.
        // Use uri options is simpler then add urlencoded username to connection string.
        if (!empty($config['user'])) {
            $uriOptions['username'] = $config['user'];
        }

        // Add password to the array with uri options.
        // Use uri options is simpler then add urlencoded password to connection string .
        if (!empty($config['pass'])) {
            $uriOptions['password'] = $config['pass'];
        }

        // Add authentication database to the array with uri options.
        if (!empty($config['authenticationDatabase'])) {
            $uriOptions['authSource'] = $config['authenticationDatabase'];
        }

        // Create new Client instance.
        $client = new static($connectionString, $uriOptions, $driverOptions);
        // Store connected db name.
        $client->setDbName($config['db']);

        return $client;
    }

    /**
     * {@inheritdoc}
     */
    public static function fromUserInput(InputInterface $input, array $uriOptions = [], array $driverOptions = [])
    {
        // Get config array from Input instance. Options array has lower priority than arguments array.
        $config = array_merge(
            array_filter($input->getOptions()),
            array_filter($input->getArguments())
        );

        return static::fromArray($config, $uriOptions, $driverOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function getDbName()
    {
        return $this->dbName;
    }

    /**
     *{@inheritdoc}
     */
    public function setDbName($dbName)
    {
        $this->dbName = $dbName;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getBuilder()
    {
        return $this->builder;
    }

    /**
     * {@inheritdoc}
     */
    public function setBuilder(MongoQueryBuilder $queryBuilder)
    {
        $this->builder = $queryBuilder;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDatabase()
    {
        return $this->selectDatabase($this->getDbName());
    }

    /**
     * {@inheritdoc}
     */
    public function executeQuery($collectionName, Query $query, ReadPreference $readPreference = null)
    {
        $namespace = $this->getDatabase()->selectCollection($collectionName)->getNamespace();

        return $this->getManager()->executeQuery($namespace, $query, $readPreference);
    }

    /**
     * {@inheritdoc}
     */
    public function executeCommand(Command $command, ReadPreference $readPreference = null)
    {
        return $this->getManager()->executeCommand($this->getDbName(), $command, $readPreference);
    }

    /**
     * {@inheritdoc}
     */
    public function executeDatabaseCommand($command, array $options = [])
    {
        return $this->getDatabase()->command($command, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function checkConnection()
    {
        if (!($this->getDatabase()->listCollections() instanceof CollectionInfoIterator)) {
            throw new UnexpectedValueException('listCollections command did not return valid object.');
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function executeSqlStatement(Statement $statement)
    {
        $this->getBuilder()->setStatement($statement);

        // @todo remove dummy result.
        return print_r($statement, true);
    }
}
