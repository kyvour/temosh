<?php


namespace Temosh\Mongo\Connection;

/**
 * Interface OptionsValidatorInterface
 */
interface OptionsValidatorInterface
{

    /**
     * @return callable
     *  The closure for host validation.
     */
    public function validateHost();

    /**
     * @return callable
     *  The closure for port validation.
     */
    public function validatePort();

    /**
     * @return callable
     *  The closure for user validation.
     */
    public function validateUser();

    /**
     * @return callable
     *  The closure for password validation.
     */
    public function validatePass();

    /**
     * @return callable
     *  The closure for password validation.
     */
    public function validateDbName();
}
