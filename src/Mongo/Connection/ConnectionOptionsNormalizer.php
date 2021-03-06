<?php

namespace Temosh\Mongo\Connection;

/**
 * Class OptionsValidator
 * Contains a set of methods for connection options validation.
 */
class ConnectionOptionsNormalizer implements ConnectionOptionsNormalizerInterface
{

    /**
     * @return callable
     *  Closure for non-empty required string normalization.
     */
    protected function normalizeString()
    {
        $normalizer = function ($value) {
            if (empty($value)) {
                return '';
            }

            return trim((string) $value);
        };

        return $normalizer;
    }

    /**
     * @return callable
     *  Closure for integer validation.
     */
    protected function normalizeInteger()
    {
        $normalizer = function ($value) {
            $value = trim($value);

            return $value ? (int) $value : 0;
        };

        return $normalizer;
    }

    /**
     * {@inheritdoc}
     */
    public function normalizePort()
    {
        return $this->normalizeInteger();
    }

    /**
     * {@inheritdoc}
     */
    public function normalizeHost()
    {
        return $this->normalizeString();
    }

    /**
     * {@inheritdoc}
     */
    public function normalizeDbName()
    {
        return $this->normalizeString();
    }

    /**
     * {@inheritdoc}
     */
    public function normalizeUser()
    {
        return $this->normalizeString();
    }
}
