<?php

namespace Temosh\Sql\Query;

use Temosh\Sql\Exception\ParseException;
use Temosh\Sql\Normalizer\NormalizerInterface;

/**
 * Class for sql query parser.
 */
class Query implements QueryInterface
{

    const QUERY_PATTERN = [
        'select' => 'select\s+(?<select>.*?)',
        'from' => '\s+from\s+(?<from>.*?)',
        'where' => '(\s+where\s+(?<where>.*?))?',
        'order' => '(\s+order\s+by\s+(?<order>.*?))?',
        'limit' => '(\s+limit\s+(?<limit>\d+?))?',
        'offset' => '(\s+(offset|skip)\s+(?<offset>\d+?))?',
    ];

    const QUERY_PART_REGEXP = [
        'select' => '/^[(a-z0-9\-\_\.\*]+$/i',
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
        // The resulting parsed array with parts of sql query.
        $sqlQueryArray = [];

        // Build regex for sql query.
        $regex = '@^\s*' . implode('', static::QUERY_PATTERN) . '\s*$@';
        $string = $this->getQueryString();
        $groups = [];

        $isMatch = @preg_match($regex, $string, $groups);
        if (!$isMatch) {
            // Unsupported query format.
            throw new ParseException('Unable to parse the query.');
        }

        // Leave only required (named) groups in regex result.
        $groups = array_intersect_key($groups, static::QUERY_PATTERN);

        // Parse select...from part.
        $sqlQueryArray['select'] = $this->parseSelect($groups['select']);

        return $sqlQueryArray;
    }


    /**
     * Parses column names for select query.
     *
     * @param string $partsString
     *  String with comma separated columns list.
     *
     * @return array
     * @throws \Temosh\Sql\Exception\ParseException
     */
    protected function parseSelect($partsString)
    {
        $parts = explode(',', $partsString);
        $parts = array_filter(array_map('trim', $parts));

        if (empty($parts)) {
            throw new ParseException('Unable to parse column names in the query.');
        }

        foreach ($parts as $part) {
            if (!preg_match((static::QUERY_PART_REGEXP)['select'], $part)) {
                throw new ParseException(sprintf('Invalid column name %s', $part));
            }
        }

        return $parts;
    }
}
