<?php


namespace Temosh\Mongo\Connection;

/**
 * Interface OptionsValidatorInterface
 */
interface OptionsValidatorInterface
{

    /**
     * @return \Closure
     *  The closure for host validation.
     */
    public function validateHost();

    /**
     * @return \Closure
     *  The closure for port validation.
     */
    public function validatePort();

    /**
     * @return \Closure
     *  The closure for user validation.
     */
    public function validateUser();

    /**
     * @return \Closure
     *  The closure for password validation.
     */
    public function validatePass();

    /**
     * @return \Closure
     *  The closure for password validation.
     */
    public function validateDbName();
}
