<?php

namespace Temosh\Mongo\Client;

use MongoDB\Driver\Command;
use MongoDB\Driver\Query;
use MongoDB\Driver\ReadPreference;
use PhpMyAdmin\SqlParser\Statements\SelectStatement;
use Symfony\Component\Console\Input\InputInterface;
use Temosh\Mongo\Query\MongoQueryBuilder;

interface ExtendedClientInterface
{

    /**
     * Creates new Client instance from config array.
     *
     * @param array $config
     *  The configuration array.
     * @param array $uriOptions
     *  Additional connection string options
     * @param array $driverOptions
     *  Driver-specific options
     *
     * @return static
     */
    public static function fromArray(array $config, array $uriOptions = [], array $driverOptions = []);

    /**
     * Creates new Client instance from Input object.
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *  The input instance.
     * @param array $uriOptions
     *  Additional connection string options
     * @param array $driverOptions
     *  Driver-specific options
     *
     * @return static
     */
    public static function fromUserInput(InputInterface $input, array $uriOptions = [], array $driverOptions = []);

    /**
     * @return string
     *  The name of connected database.
     */
    public function getDbName();

    /**
     * @param string $dbName
     *  The name of connected database
     *
     * @return $this
     */
    public function setDbName($dbName);

    /**
     * @return \MongoDB\Database
     *  The database instance.
     */
    public function getDatabase();

    /**
     * @param string $collectionName
     *  Name of the collection to select.
     * @param array $options
     *  Collection constructor options.
     *
     * @return \MongoDB\Collection
     *  The collection instance.
     */
    public function getCollection($collectionName, array $options = []);

    /**
     * @return bool
     *  True if connection to the MongoDb is established.
     */
    public function checkConnection();

    /**
     * Executes sql statement.
     *
     * @param \PhpMyAdmin\SqlParser\Statements\SelectStatement $statement
     *  The sql statement.
     *
     * @return array<*,array>
     */
    public function executeSelectStatement(SelectStatement $statement);

    /**
     * @return \Temosh\Mongo\Query\MongoQueryBuilder
     *  The query builder instance.
     */
    public function getBuilder();

    /**
     * @param \Temosh\Mongo\Query\MongoQueryBuilder $queryBuilder
     *  The query builder instance.
     *
     * @return mixed
     */
    public function setBuilder(MongoQueryBuilder $queryBuilder);
}
