<?php

namespace Temosh\Mongo\Query;

use PhpMyAdmin\SqlParser\Statement;

class MongoQueryBuilder
{

    /**
     * @var \PhpMyAdmin\SqlParser\Statement
     *  Sql statement instance.
     */
    private $statement;

    /**
     * @return \PhpMyAdmin\SqlParser\Statement
     *  Sql statement instance.
     */
    public function getStatement()
    {
        return $this->statement;
    }

    /**
     * @param \PhpMyAdmin\SqlParser\Statement $statement
     *
     * @return $this
     */
    public function setStatement(Statement $statement)
    {
        $this->statement = $statement;

        return $this;
    }
}
