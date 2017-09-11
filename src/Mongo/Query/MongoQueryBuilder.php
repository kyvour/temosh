<?php

namespace Temosh\Mongo\Query;

use PhpMyAdmin\SqlParser\Components\Expression;
use PhpMyAdmin\SqlParser\Statements\SelectStatement;
use Temosh\Mongo\Exception\MongoQueryBuildException;

class MongoQueryBuilder implements MongoQueryBuilderInterface
{

    const OPERATORS_MAP = [
        '<>' => '$ne',
        '>=' => '$gte',
        '<=' => '$lte',
        '=' => '$eq',
        '<' => '$lt',
        '>' => '$ge',
    ];

    const OPERATORS_MAP_REVERSED = [
        '$ne' => '<>',
        '$gte' => '>=',
        '$lte' => '<=',
        '$eq' => '=',
        '$lt' => '<',
        '$ge' => '>',
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

        return $collectionName;
    }

    /**
     * {@inheritdoc}
     */
    public function getFields()
    {
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
    public function getConditions()
    {
        $conditions = $this->getStatement()->where;
        if (!$conditions) {
            return [];
        }

        // Break all conditions to OR groups.
        $groups = [];
        $orNumber = 0;

        foreach ($conditions as $condition) {
            // Convert expression to MongoDB array and add to current group.
            if (!$condition->isOperator) {
                $groups[$orNumber][] = $this->convertConditionOperation($condition->expr);
                continue;
            }

            // Do nothing if there is just AND operator.
            if ($condition->expr === 'AND') {
                continue;
            }

            // Just increase group number if there is OR operator.
            if($condition->expr === 'OR') {
                $orNumber++;
                continue;
            }

            // Throw exception when there isn't field condition and OR or AND operator.
            throw new MongoQueryBuildException(sprintf('Unsupported logical operator %s', $condition->expr));
        }

        // Throw exception if there was only operators without field conditions.
        if (empty($groups)) {
            throw new MongoQueryBuildException('Unable to build WHERE statement.');
        }

        // One group means that WHERE part doesn't have OR conditions.
        if (count($groups) === 1) {
            return reset($groups);
        }

        $filter = ['$or' => []];
        foreach ($groups as $group) {
            $filter['$or'][] = count($group) > 1 ? ['$and' => $group] : reset($group);
        }

        return $filter;
    }

    /**
     * Converts sql expression to MongoDB driver format.
     *
     * @param $expr
     *  String sql expression.
     *
     * @return array
     */
    protected function convertConditionOperation($expr)
    {
        foreach (static::OPERATORS_MAP as $op => $opm) {
            $parts = explode($op, $expr);

            if (count($parts) === 2) {
                list($key, $value) = array_map('trim', $parts);

                return [$key => [$opm => $value]];
            }
        }

        throw new MongoQueryBuildException(sprintf('Unable to build filter from expression %s', $expr));
    }

    /**
     * {@inheritdoc}
     */
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
    public function getSkip()
    {
        $limitObject = $this->getStatement()->limit;
        if (!$limitObject) {
            return null;
        }

        return (int) $limitObject->offset;
    }
}
