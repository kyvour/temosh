<?php

namespace Temosh\Sql\Query;

use PhpMyAdmin\SqlParser\Components\Condition;
use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Utils\Query as ParserQuery;
use Temosh\Sql\Exception\ParseException;
use Temosh\Sql\Normalizer\NormalizerInterface;

/**
 * Class for sql query parser.
 */
class Query implements QueryInterface
{

    /**
     * Allowed flags for \PhpMyAdmin\SqlParser\Utils\Query.
     */
    const ALLOWED_FLAGS = [
        'querytype' => true,
        'is_select' => true,
        'select_from' => true,
        'order' => true,
        'limit' => true,
        'offset' => true,
    ];

    const ALLOWED_CONDITION_OPERATIONS = [
        '=',
        '<>',
        '>',
        '>=',
        '<',
        '<=',
    ];

    const ALLOWED_CONDITION_OPERATORS = [
        'and',
        'or',
    ];

    /**
     * Array with exit command.
     */
    const EXIT_COMMANDS = [
        'exit',
        'quit',
        'die',
        'q',
    ];

    /**
     * @var string
     *  Sql query string.
     */
    private $queryString = '';

    /**
     * @var \Temosh\Sql\Normalizer\NormalizerInterface
     *  Normalizer instance.
     */
    private $normalizer;

    /**
     * Parser constructor.
     *
     * @param \Temosh\Sql\Normalizer\NormalizerInterface $normalizer
     *  Normalizer instance.
     */
    public function __construct(NormalizerInterface $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryString()
    {
        return $this->queryString;
    }

    /**
     * {@inheritdoc}
     */
    public function setQueryString($queryString)
    {
        $this->queryString = $this->normalizer->normalizeString($queryString);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isExitCommand()
    {
        $command = $this->getQueryString();

        return in_array($command, static::EXIT_COMMANDS, true);
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty()
    {
        $string = $this->getQueryString();

        return empty($string);
    }

    /**
     * {@inheritdoc}
     */
    public function parse()
    {
        $query = $this->normalizer->normalizeStructure($this->getQueryString());

        try {
            $parser = new Parser($query, true);
        } catch (\Exception $e) {
            throw new ParseException('Unable to parse query string', 0, $e);
        }

        /** @var \PhpMyAdmin\SqlParser\Statements\SelectStatement $statement */
        $statement = @$parser->statements[0];
        if (!$statement) {
            throw new ParseException('Unable to parse query string');
        }

        // Get query flags.
        $flags = ParserQuery::getFlags($statement);
        $flags = array_filter($flags);

        // Check if query is select query.
        if (empty($flags['is_select'])) {
            throw new ParseException('Parse error. String is not SELECT query');
        }

        // Check if query is select...from query.
        if (empty($flags['select_from'])) {
            throw new ParseException('Parse error. String is not SELECT...FROM query');
        }

        // Check if query has supported structure
        $unavailableFlags = array_diff_key($flags, static::ALLOWED_FLAGS);
        if (count($unavailableFlags)) {
            throw new ParseException('Parse error. Unsupported query structure');
        }

        // Check collections quantity.
        if (count($statement->from) > 1) {
            throw new ParseException('Parse error. Only one collection if FROM section is supported.');
        }

        if (empty($statement->where)) {
            return $statement;
        }

        // Check 'where' part for unavailable operations or operators.
        $unsupportedOperators = array_filter($statement->where, function (Condition $condition) {
            $expr = strtolower($condition->expr);
            return $condition->isOperator && !in_array($expr, static::ALLOWED_CONDITION_OPERATORS, true);
        });
        if (count($unsupportedOperators)) {
            throw new ParseException('Unsupported condition operators. Only "AND" and "OR" operators are supported.');
        }

        // Get list of conditions without operators.
        $operations = array_filter($statement->where, function (Condition $condition) {
            return !$condition->isOperator;
        });

        // Check for brackets in conditions.
        $bracketsOperation = array_filter($operations, function (Condition $condition) {
            return preg_match('/[\(\[\{\)\]\}]/', $condition->expr);
        });
        if (count($bracketsOperation)) {
            throw new ParseException("Brackets in conditions aren't supported");
        }

        return $statement;
    }
}
