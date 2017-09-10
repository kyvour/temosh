<?php

namespace Temosh\Mongo\Connection;

use Symfony\Component\Console\Exception\InvalidArgumentException;

/**
 * Class OptionsValidator
 * Contains a set of methods for connection options validation.
 */
class OptionsValidator implements OptionsValidatorInterface
{

    /**
     * @return callable
     *  Closure for non-empty required string validation.
     */
    protected function validateRequiredString()
    {
        $validator = function ($value) {
            if (!is_string($value)) {
                throw new InvalidArgumentException('The entered value should be a string');
            }

            if (empty($value)) {
                throw new InvalidArgumentException('The entered value cannot be empty');
            }

            return $value;
        };

        return $validator;
    }

    /**
     * @return callable
     *  Closure for non-empty optional string validation.
     */
    protected function validateOptionalString()
    {
        $validator = function ($value) {
            // The value can be empty. Returns null in this case.
            return $value ?: '';
        };

        return $validator;
    }

    /**
     * {@inheritdoc}
     */
    public function validatePort()
    {
        $validator = function ($value) {
            if (!is_int($value)) {
                throw new InvalidArgumentException('The entered value should be an integer number');
            }

            if ($value < 1 || $value > 65345) {
                throw new InvalidArgumentException('The entered value should be between 0 - 65535');
            }

            return $value;
        };

        return $validator;
    }

    /**
     * {@inheritdoc}
     */
    public function validateHost()
    {
        return $this->validateRequiredString();
    }

    /**
     * {@inheritdoc}
     */
    public function validateDbName()
    {
        return $this->validateRequiredString();
    }

    /**
     * {@inheritdoc}
     */
    public function validateUser()
    {
        return $this->validateOptionalString();
    }

    /**
     * {@inheritdoc}
     */
    public function validatePass()
    {
        return $this->validateOptionalString();
    }
}
