<?php

namespace Temosh\Console\Helper;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface QuestionHelperInterface
{

    /**
     * Asks user for the database name.
     *
     * @param \Symfony\Component\Console\Input\InputInterface|null $input
     * @param \Symfony\Component\Console\Output\OutputInterface|null $output
     *
     * @return string The name of the database to connect to.
     *  The name of the database to connect to.
     */
    public function askDbName(InputInterface $input, OutputInterface $output);

    /**
     * Asks user for the host.
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return string The host to connect to.
     *  The host to connect to.
     */
    public function askHost(InputInterface $input, OutputInterface $output);

    /**
     * Asks user for the port.
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int The port to connect to.
     * The port to connect to.
     */
    public function askPort(InputInterface $input, OutputInterface $output);

    /**
     * Asks user for the database user.
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return string The database user.
     * The database user.
     */
    public function askUser(InputInterface $input, OutputInterface $output);

    /**
     * Asks user for the database password.
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return string The database password.
     * The database password.
     */
    public function askPass(InputInterface $input, OutputInterface $output);
}
