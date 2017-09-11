<?php

namespace Temosh\Sql\Normalizer;

use Temosh\Sql\Exception\ParseSqlException;

/**
 * Normalizer class for SQL query.
 */
class SqlNormalizer implements SqlNormalizerInterface
{

    /**
     * An array with query regex patterns.
     */
    const QUERY_STRUCTURE_PATTERN = [
        'select' => 'select\s+(?<select>.*?)',
        'from' => '\s+from\s+(?<from>.*?)',
        'where' => '(\s+where\s+(?<where>.*?))?',
        'order' => '(\s+order\s+by\s+(?<order>.*?))?',
        'limit' => '(\s+limit\s+(?<limit>\d+?))?',
        'offset' => '(\s+(offset|skip)\s+(?<offset>\d+?))?',
    ];

    /**
     * An array with query keywords.
     */
    const QUERY_STRUCTURE_KEYWORDS = [
        'select' => 'select',
        'from' => 'from',
        'where' => 'where',
        'order' => 'order by',
        'limit' => 'limit',
        'offset' => 'offset',
    ];

    /**
     * {@inheritdoc}
     */
    public function normalizeString($queryString)
    {
        // Cast to string.
        $queryString = (string) $queryString;
        // Trim whitespaces.
        $queryString = trim($queryString);
        // Trim trailing semicolons.
        $queryString = rtrim($queryString, ';');

        return trim($queryString);
    }

    /**
     * Parses and checks query structure.
     *
     * @param $string
     *  Sql query string.
     *
     * @return string
     * @throws \Temosh\Sql\Exception\ParseSqlException
     */
    public function normalizeStructure($string)
    {
        // Build regex for sql query.
        $regex = '/^\s*' . implode('', static::QUERY_STRUCTURE_PATTERN) . '\s*$/i';
        $groups = [];

        $isMatch = @preg_match($regex, $string, $groups);
        if (!$isMatch) {
            // Unsupported query format.
            throw new ParseSqlException('Illegal query structure.');
        }

        // Leave only required (named) groups in regex result.
        $groups = array_intersect_key($groups, static::QUERY_STRUCTURE_PATTERN);
        $groups = array_filter(array_map('trim', $groups));

        // Check if 'from' section exists.
        if (!isset($groups['select']) || trim($groups['select']) === '') {
            throw new ParseSqlException('Illegal query structure: "select" section does not exist or empty.');
        }

        // Check if 'from' section exists.
        if (!isset($groups['from']) || trim($groups['from']) === '') {
            throw new ParseSqlException('Illegal query structure: "from" section does not exist or empty.');
        }

        // Check if 'offset' section exists together with 'limit'.
        if (!isset($groups['limit']) && isset($groups['offset'])) {
            throw new ParseSqlException('Illegal query structure: "offset" can be used only with "limit" statement.');
        }

        // Join back query parts.
        $query = '';
        foreach ($groups as $name => $group) {
            $query .= static::QUERY_STRUCTURE_KEYWORDS[$name] . ' ' . $group . ' ';
        }

        return trim($query);
    }
}
