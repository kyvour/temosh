<?php

namespace Temosh\Mongo\Connection;

/**
 * Class OptionsValidator
 * Contains a set of methods for connection options validation.
 */
class OptionsNormalizer implements OptionsNormalizerInterface
{

    /**
     * @return \Closure
     *  Closure for non-empty required string normalization.
     */
    protected function normalizeString()
    {
        return function ($value) {
            return $value ? trim($value) : '';
        };
    }

    /**
     * @return \Closure
     *  Closure for integer validation.
     */
    protected function normalizeInt()
    {
        return function ($value) {
            $value = trim($value);

            return $value ? (int)$value : 0;
        };
    }

    /**
     * {@inheritdoc}
     */
    public function normalizePort()
    {
        return $this->normalizeInt();
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
