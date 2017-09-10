<?php

namespace Temosh\Sql\Normalizer;

use Temosh\Sql\Exception\ParseException;

/**
 * Normalizer class for SQL query.
 */
class Normalizer implements NormalizerInterface
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
        // convert string to lowercase.
        $queryString = strtolower($queryString);
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
     * @throws \Temosh\Sql\Exception\ParseException
     */
    public function normalizeStructure($string)
    {
        // Build regex for sql query.
        $regex = '/^\s*' . implode('', static::QUERY_STRUCTURE_PATTERN) . '\s*$/i';
        $groups = [];

        $isMatch = @preg_match($regex, $string, $groups);
        if (!$isMatch) {
            // Unsupported query format.
            throw new ParseException('Illegal query structure.');
        }

        // Leave only required (named) groups in regex result.
        $groups = array_intersect_key($groups, static::QUERY_STRUCTURE_PATTERN);

        // Check if 'from' section exists.
        if (!isset($groups['select']) || trim($groups['select']) === '') {
            throw new ParseException('Illegal query structure: "select" section does not exist or empty.');
        }

        // Check if 'from' section exists.
        if (!isset($groups['from']) || trim($groups['from']) === '') {
            throw new ParseException('Illegal query structure: "from" section does not exist or empty.');
        }

        // Check where section.
        if (isset($groups['where']) && trim($groups['where']) === '') {
            throw new ParseException('Illegal query structure: "where" section is empty.');
        }

        // Check order by section.
        if (isset($groups['order']) && trim($groups['order']) === '') {
            throw new ParseException('Illegal query structure: "order by" section is empty.');
        }

        // Check limit section.
        if (isset($groups['limit']) && trim($groups['limit']) === '') {
            throw new ParseException('Illegal query structure: "limit" section is empty.');
        }

        // Check offset section.
        if (isset($groups['offset']) && trim($groups['offset']) === '') {
            throw new ParseException('Illegal query structure: "offset" section is empty.');
        }

        // Check if 'offset' section exists together with 'limit'.
        if (!isset($groups['limit']) && isset($groups['offset'])) {
            throw new ParseException('Illegal query structure: "offset" can be used only with "limit" statement.');
        }

        // Join back query parts.
        $query = '';
        foreach ($groups as $name => $group) {
            $query .= static::QUERY_STRUCTURE_KEYWORDS[$name] . ' ' . $group . ' ';
        }

        return trim($query);
    }
}
