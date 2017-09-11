<?php

namespace Temosh\Mongo\Query;

use PhpMyAdmin\SqlParser\Statements\SelectStatement;

interface MongoQueryBuilderInterface
{

    /**
     * @return \PhpMyAdmin\SqlParser\Statements\SelectStatement
     *  Sql statement instance.
     */
    public function getStatement();

    /**
     * @param \PhpMyAdmin\SqlParser\Statements\SelectStatement $statement
     *
     * @return $this
     */
    public function setStatement(SelectStatement $statement);

    /**
     * @return string
     *  The collection name for query.
     */
    public function getCollectionName();

    /**
     * Returns an array of fields for the query.
     *
     * @return array
     */
    public function getFields();

    /**
     * Returns an array of conditions for the query.
     *
     * @return array
     */
    public function getConditions();

    /**
     * Returns an array with sorting for the query.
     *
     * @return array|null
     */
    public function getSort();

    /**
     * Returns a limit number for the query.
     *
     * @return int|null
     */
    public function getLimit();

    /**
     * Returns an offset (skip) number for the query.
     *
     * @return int|null
     */
    public function getSkip();
}
