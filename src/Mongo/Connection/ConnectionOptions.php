<?php

declare(strict_types=1);

namespace Temosh\Mongo\Connection;

/**
 * Class Options
 *
 * Contains default options for connection to MongoDB.
 */
class ConnectionOptions
{
    /**
     * @var string
     *  Default MongoDB host.
     */
    const DEFAULT_HOST = '127.0.0.1';

    /**
     * @var int
     *  Default MongoDB port.
     */
    const DEFAULT_PORT = 27017;

    /**
     * @var string
     *  The default username for authentication.
     */
    const DEFAULT_USERNAME = '';

    /**
     * @var string
     *  The default password for authentication.
     */
    const DEFAULT_PASSWORD = '';

    /**
     * @var string
     *  The name of authentication database.
     */
    const DEFAULT_AUTH_DB = '';
}
