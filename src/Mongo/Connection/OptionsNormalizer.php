<?php

namespace Temosh\Mongo\Connection;

/**
 * Class OptionsValidator
 * Contains a set of methods for connection options validation.
 */
class OptionsNormalizer implements OptionsNormalizerInterface
{

    /**
     * @return callable
     *  Closure for non-empty required string normalization.
     */
    protected function stringNormalizer()
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
    protected function integerNormalizer()
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
        return $this->integerNormalizer();
    }

    /**
     * {@inheritdoc}
     */
    public function normalizeHost()
    {
        return $this->stringNormalizer();
    }

    /**
     * {@inheritdoc}
     */
    public function normalizeDbName()
    {
        return $this->stringNormalizer();
    }

    /**
     * {@inheritdoc}
     */
    public function normalizeUser()
    {
        return $this->stringNormalizer();
    }
}
