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
}
