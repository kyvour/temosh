<?php

namespace Temosh\Sql\Normalizer;

/**
 * Normalizer class for SQL query.
 */
class Normalizer implements NormalizerInterface
{

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

        return $queryString;
    }
}
