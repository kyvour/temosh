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
     * {@inheritdoc}
     */
    public function getStatement()
    {
        return $this->statement;
    }

    /**
     * {@inheritdoc}
     */
    public function setStatement(Statement $statement)
    {
        $this->statement = $statement;

        return $this;
    }
}
