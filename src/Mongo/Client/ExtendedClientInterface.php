<?php

namespace Temosh\Mongo\Client;

use MongoDB\Driver\Command;
use MongoDB\Driver\Query;
use MongoDB\Driver\ReadPreference;
use PhpMyAdmin\SqlParser\Statement;
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
     * Proxy method for \MongoDB\Driver\Manager::executeQuery() for connected database.
     *
     * @param string $collectionName
     *  The name of collection
     * @param \MongoDB\Driver\Query $query
     *  A MongoDB\Driver\Query to execute.
     * @param ReadPreference $readPreference
     *  Optionally, a MongoDB\Driver\ReadPreference to route the command to.
     *  If none given, defaults to the Read Preferences set by the MongoDB Connection URI.
     *
     * @return \MongoDB\Driver\Cursor
     * @throws \MongoDB\Driver\Exception\Exception
     * @throws \MongoDB\Driver\Exception\AuthenticationException
     *  If authentication is needed and fails
     * @throws \MongoDB\Driver\Exception\ConnectionException
     *  If connection to the server fails for other then authentication reasons
     * @throws \MongoDB\Driver\Exception\RuntimeException
     *  On other errors (invalid command, command arguments, ...)
     */
    public function executeQuery($collectionName, Query $query, ReadPreference $readPreference = null);

    /**
     * Proxy method for \MongoDB\Driver\Manager::executeCommand() for connected database.
     *
     * @param \MongoDB\Driver\Command $command
     *  The command document.
     * @param \MongoDB\Driver\ReadPreference $readPreference
     *  Optionally, a MongoDB\Driver\ReadPreference to route the command to.
     *  If none given, defaults to the Read Preferences set by the MongoDB Connection URI.
     *
     * @return \MongoDb\Driver\Cursor
     * @throws \MongoDB\Driver\Exception\Exception
     * @throws \MongoDB\Driver\Exception\AuthenticationException
     *  If authentication is needed and fails.
     * @throws \MongoDB\Driver\Exception\ConnectionException
     *  If connection to the server fails for other then authentication reasons.
     * @throws \MongoDB\Driver\Exception\RuntimeException
     *  On other errors (invalid command, command arguments, ...)
     * @throws \MongoDB\Driver\Exception\DuplicateKeyException
     *  If a write causes Duplicate Key error.
     * @throws \MongoDB\Driver\Exception\WriteException
     *  On Write Error
     * @throws \MongoDB\Driver\Exception\WriteConcernException
     *  On Write Concern failure
     */
    public function executeCommand(Command $command, ReadPreference $readPreference = null);

    /**
     * Proxy method for \MongoDB\Database::command() for connected database.
     *
     * @see \MongoDB\Operation\DatabaseCommand::__construct() for supported options
     *
     * @param array|object $command Command document
     * @param array $options Options for command execution
     *
     * @return \MongoDb\Driver\Cursor
     * @throws \MongoDB\Exception\InvalidArgumentException
     *  For parameter/option parsing errors
     * @throws \MongoDB\Driver\Exception\RuntimeException
     *  For other driver errors (e.g. connection errors)
     */
    public function executeDatabaseCommand($command, array $options = []);

    /**
     * @return \MongoDB\Database
     *  The database instance.
     */
    public function getDatabase();

    /**
     * @return bool
     *  True if connection to the MongoDb is established.
     */
    public function checkConnection();

    /**
     * Executes sql statement.
     *
     * @param \PhpMyAdmin\SqlParser\Statement $statement
     *  The sql statement.
     *
     * @return string
     */
    public function executeSqlStatement(Statement $statement);

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
    public function setQueryBuilder(MongoQueryBuilder $queryBuilder);
}
