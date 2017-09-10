<?php

namespace Temosh\Console;

use Symfony\Component\Console\Helper\HelperInterface;

interface MongoShellInterface
{

    /**
     * Set helper to the application.
     *
     * @param \Symfony\Component\Console\Helper\HelperInterface $helper
     *
     * @return $this
     */
    public function setHelper(HelperInterface $helper);

    /**
     * @return \Temosh\Sql\Query\QueryInterface
     *  Instance of SQL query parser.
     */
    public function getSqlQuery();
}
