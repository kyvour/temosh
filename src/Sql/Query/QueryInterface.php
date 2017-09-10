<?php

namespace Temosh\Sql\Query;

/**
 * Interface for sql query parsers.
 */
interface QueryInterface
{

    /**
     * @return string
     *  SQL query string.
     */
    public function getQueryString();

    /**
     * @param string $queryString
     *  SQL query string.
     *
     * @return $this
     */
    public function setQueryString($queryString);

    /**
     * @return bool
     *  True if query string is exit command and false otherwise.
     */
    public function isExitCommand();

    /**
     * @return bool
     *  True if query string is empty and false otherwise.
     */
    public function isEmpty();

    /**
     * Parses SQL query string.
     *
     * @return \PhpMyAdmin\SqlParser\Statements\SelectStatement
     *  Parsed query statement.
     */
    public function parse();
}
