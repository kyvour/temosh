<?php

declare(strict_types=1);

namespace Temosh\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Temosh\Mongo\Connection\ConnectionOptions;

/**
 * Class SelectCommand
 *
 * The command for retrieving data from MongoDB.
 */
abstract class BaseCommand extends Command
{
    /**
     * {@inheritdoc}
     *
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();

        $definition = new InputDefinition(
            [
                $this->hostOption(),
                $this->portOption(),
                $this->usernameOption(),
                $this->passwordOption(),
                $this->authenticationDatabaseOption(),
                $this->dbArgument(),
            ]
        );

        $this->setName('read')
            ->setDescription('Selects data from MongoDB collection.')
            ->setDefinition($definition);
    }

    /**
     * Returns the database name argument definition.
     *
     * @return \Symfony\Component\Console\Input\InputArgument
     */
    protected function dbArgument(): InputArgument
    {
        return new InputArgument(
            'db',
            InputArgument::REQUIRED,
            'the database name to connect to'
        );
    }

    /**
     * Returns the server address option definition.
     *
     * @return \Symfony\Component\Console\Input\InputOption
     */
    protected function hostOption(): InputOption
    {
        return new InputOption(
            'host',
            'H',
            InputOption::VALUE_OPTIONAL,
            'the server address to connect to',
            ConnectionOptions::DEFAULT_HOST
        );
    }

    /**
     * Returns the port option definition.
     *
     * @return \Symfony\Component\Console\Input\InputOption
     */
    protected function portOption(): InputOption
    {
        return new InputOption(
            'port',
            'P',
            InputOption::VALUE_OPTIONAL,
            'the port number to connect to',
            ConnectionOptions::DEFAULT_PORT
        );
    }

    /**
     * Username option definition.
     *
     * @return \Symfony\Component\Console\Input\InputOption
     */
    protected function usernameOption(): InputOption
    {
        return new InputOption(
            'user',
            'u',
            InputOption::VALUE_OPTIONAL,
            'the username for authentication',
            ConnectionOptions::DEFAULT_USERNAME
        );
    }

    /**
     * Password option definition.
     *
     * @return \Symfony\Component\Console\Input\InputOption
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function passwordOption(): InputOption
    {
        return new InputOption(
            'pass',
            'p',
            InputOption::VALUE_OPTIONAL,
            'the password for authentication',
            ConnectionOptions::DEFAULT_PASSWORD
        );
    }

    /**
     * Authentication Database option definition.
     *
     * @return \Symfony\Component\Console\Input\InputOption
     */
    protected function authenticationDatabaseOption(): InputOption
    {
        return new InputOption(
            'authenticationDatabase',
            null,
            InputOption::VALUE_OPTIONAL,
            'the name for authentication database',
            ConnectionOptions::DEFAULT_AUTH_DB
        );
    }

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        parent::interact($input, $output);

        /** @var \Temosh\Console\Helper\QuestionHelperInterface $helper */
        $helper = $this->getHelper('temosh_question');

        // Ask user for the host if option was entered without value.
        if ($input->getOption('host') === null) {
            $input->setOption('host', $helper->askHost($input, $output));
        }

        // Ask user for the port if option was entered without value.
        if ($input->getOption('port') === null) {
            $input->setOption('port', $helper->askPort($input, $output));
        }

        // Ask user for the username if option was entered without value.
        if ($input->getOption('user') === null) {
            $input->setOption('user', $helper->askUser($input, $output));
        }

        // Ask user for the password if option was entered without value.
        if ($input->getOption('pass') === null) {
            $input->setOption('pass', $helper->askPass($input, $output));
        }

        // Ask user for the database name if argument is missed.
        if ($input->getArgument('db') === null) {
            $input->setArgument('db', $helper->askDbName($input, $output));
        }

        // Set auth database to null if its value is empty.
        $authDb = trim((string) $input->getOption('authenticationDatabase'));
        $input->setOption('authenticationDatabase', $authDb);
    }
}
