<?php


namespace Temosh\Mongo\Connection;

/**
 * Interface OptionsValidatorInterface
 */
interface OptionsNormalizerInterface
{

    /**
     * @return \Closure
     *  The closure for host normalization.
     */
    public function normalizeHost();

    /**
     * @return \Closure
     *  The closure for port normalization.
     */
    public function normalizePort();

    /**
     * @return \Closure
     *  The closure for user normalization.
     */
    public function normalizeUser();

    /**
     * @return \Closure
     *  The closure for password normalization.
     */
    public function normalizeDbName();
}
