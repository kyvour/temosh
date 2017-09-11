<?php

namespace Temosh\Mongo\Query;

use PhpMyAdmin\SqlParser\Components\Expression;
use PhpMyAdmin\SqlParser\Statements\SelectStatement;
use Temosh\Mongo\Exception\MongoQueryBuildException;

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

    const SORTING_MAP = [
        'ASC' => 1,
        'DESC' => -1,
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
        $from = $this->getStatement()->from;
        if (!count($from)) {
            throw new MongoQueryBuildException('Unable retrieve collection name from SQL statement.');
        }

        $collectionName = (string) @$from[0]->table;
        if (empty($collectionName)) {
            throw new MongoQueryBuildException('Unable retrieve collection name from SQL statement.');
        }

        return  $collectionName;
    }

    /**
     * {@inheritdoc}
     */
    public function getFields() {
        $fieldsArray = $this->getStatement()->expr;
        if (empty($fieldsArray)) {
            throw new MongoQueryBuildException('Unable retrieve fields from SQL statement.');
        }

        $fields = array_map(function (Expression $expr) {
            return trim($expr->expr);
        }, $fieldsArray);

        // Just return empty array for fields (i.e. select all).
        if (in_array('*', $fields, true)) {
            return [];
        }

        // Replace 'fieldname.*' with 'fieldname' (they contain same logic).
        $fields = array_map(function ($string) {
            return preg_replace('/^(.*)?(\.\*)$/', '$1', $string);
        }, $fields);

        return array_fill_keys(array_unique($fields), 1);
    }

    /**
     * {@inheritdoc}
     */
    public function getConditions() {
        $conditions = $this->getStatement()->where;
        if (!$conditions) {
            return [];
        }

        return [];
    }

    public function getSort()
    {
        $order = $this->getStatement()->order;
        if (!$order) {
            return null;
        }

        $sort = [];
        foreach ($order as $orderObj) {
            $expr = $orderObj->expr->expr;
            $type = $orderObj->type;
            $sort[$expr] = static::SORTING_MAP[$type];
        }

        return $sort;
    }

    /**
     * {@inheritdoc}
     */
    public function getLimit()
    {
        $limitObject = $this->getStatement()->limit;
        if (!$limitObject) {
            return null;
        }

        return (int) $limitObject->rowCount;
    }

    /**
     * {@inheritdoc}
     */
    public function getSkip() {
        $limitObject = $this->getStatement()->limit;
        if (!$limitObject) {
            return null;
        }

        return (int) $limitObject->offset;
    }
}
