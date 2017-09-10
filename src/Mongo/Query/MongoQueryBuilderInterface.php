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
}
