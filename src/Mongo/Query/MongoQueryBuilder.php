<?php

namespace Temosh\Mongo\Query;

use PhpMyAdmin\SqlParser\Statements\SelectStatement;

class MongoQueryBuilder implements MongoQueryBuilderInterface
{

    const OPERATORS_MAP = [
        '='  => '$eq',
        '<>' => '$ne',
        '<'  => '$lt',
        '<=' => '$lte',
        '>'  => '$ge',
        '>=' => '$gte',
    ];

    const LOGICAL_OPERATORS_MAP = [
        'AND' => '$and',
        'OR'  => '$or',
    ];

    /**
     * @var \PhpMyAdmin\SqlParser\Statements\SelectStatement
     *  Sql statement instance.
     */
    private $statement;

    /**
     * {@inheritdoc}
     */
    public function getStatement()
    {
        return $this->statement;
    }

    /**
     * {@inheritdoc}
     */
    public function setStatement(SelectStatement $statement)
    {
        $this->statement = $statement;

        return $this;
    }

    /**
     * @return string
     *  The collection name for query.
     */
    public function getCollectionName()
    {
        return '';
    }
}
