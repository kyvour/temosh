<?php

namespace Temosh\Mongo\Query;

use PhpMyAdmin\SqlParser\Statement;

interface MongoQueryBuilderInterface
{

    /**
     * @return \PhpMyAdmin\SqlParser\Statement
     *  Sql statement instance.
     */
    public function getStatement();

    /**
     * @param \PhpMyAdmin\SqlParser\Statement $statement
     *
     * @return $this
     */
    public function setStatement(Statement $statement);
}
