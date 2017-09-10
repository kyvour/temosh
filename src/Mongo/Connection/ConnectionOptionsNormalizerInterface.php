<?php


namespace Temosh\Mongo\Connection;

/**
 * Interface OptionsValidatorInterface
 */
interface ConnectionOptionsNormalizerInterface
{

    /**
     * @return callable
     *  The closure for host normalization.
     */
    public function normalizeHost();

    /**
     * @return callable
     *  The closure for port normalization.
     */
    public function normalizePort();

    /**
     * @return callable
     *  The closure for user normalization.
     */
    public function normalizeUser();

    /**
     * @return callable
     *  The closure for password normalization.
     */
    public function normalizeDbName();
}
