<?php

namespace Temosh\Sql\Exception;

/**
 * Exception for sql query parsing.
 */
class ParseException extends QueryException
{

    const REQUIRED_QUERY_STRUCTURE = <<<query
SELECT [<Projections>] FROM <Target> [WHERE <Condition>*] [ORDER BY <Fields>* [ASC|DESC]*] [LIMIT <MaxRecords>] [[SKIP|OFFSET] <SkipRecords>]
query;
}
