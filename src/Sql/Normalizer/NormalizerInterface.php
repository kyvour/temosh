<?php

namespace Temosh\Sql\Normalizer;

/**
 * Interface for query string normalization
 */
interface NormalizerInterface
{

    /**
     * @param $queryString
     *  String for normalisation.
     *
     * @return string
     *  Normalized string.
     */
    public function normalizeString($queryString);

    /**
     * Parses and checks query structure.
     *
     * @param $string
     *  Sql query string.
     *
     * @return string
     * @throws \Temosh\Sql\Exception\ParseException
     */
    public function normalizeStructure($string);
}
