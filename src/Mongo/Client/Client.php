<?php

namespace Temosh\Mongo\Client;

use MongoDB\Driver\Cursor;
use MongoDB\Exception\InvalidArgumentException;
use MongoDB\Exception\UnexpectedValueException;
use MongoDB\Model\CollectionInfoIterator;
use PhpMyAdmin\SqlParser\Statements\SelectStatement;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\JsonSerializableNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer;
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
    public function getCollection($collectionName, array $options = [])
    {
        return $this->selectCollection($this->getDbName(), $collectionName, $options);
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
    public function executeSelectStatement(SelectStatement $statement)
    {
        $builder = $this->getBuilder()->setStatement($statement);

        $collectionName = $builder->getCollectionName();
        $fields = $builder->getFields();
        $filter = $builder->getConditions();
        $sort = $builder->getSort();
        $limit = $builder->getLimit();
        $skip = $builder->getSkip();

        $searchOptions = [
            'projection' => $fields,
        ];
        if ($sort !== null) {
            $searchOptions['sort'] = $sort;
        }
        if ($limit !== null) {
            $searchOptions['limit'] = $limit;
        }
        if ($skip !== null) {
            $searchOptions['skip'] = $skip;
        }

        $cursor = $this->getCollection($collectionName)->find([], $searchOptions);
        $result = $this->normalizeCursor($cursor);

        return $this->getTableOutput($result);
    }

    /**
     * @param \MongoDB\Driver\Cursor $cursor
     *  Converts cursor object to the results array.
     *
     * @return array
     */
    protected function normalizeCursor(Cursor $cursor) {
        $encoder = new JsonEncoder();
        $cursorIterator = new \IteratorIterator($cursor);
        $cursorIterator->rewind();

        $result = [];
        while ($document = $cursorIterator->current()) {
            $result[] = $encoder->decode($encoder->encode($document, $encoder::FORMAT), $encoder::FORMAT);
            $cursorIterator->next();
        }

        return $result;
    }

    /**
     * @param array $data
     *  Array with data.
     *
     * @return array
     *  Array with json serialized values.
     */
    protected function getTableOutput(array $data)
    {
        $encoder = new JsonEncoder();
        foreach ($data as $key => $value) {
             foreach ($value as $key2 => $value2) {
                 $data[$key][$key2] = is_scalar($value2) ? $value2 : $encoder->encode($value2, $encoder::FORMAT);
             }
        }

        return $data;
    }
}
