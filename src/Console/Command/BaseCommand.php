<?php

namespace Temosh\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Temosh\Mongo\Client\Client;
use Temosh\Mongo\Connection\Options;

/**
 * Class SelectCommand
 *
 * The command for retrieving dta from MongoDB.
 */
abstract class BaseCommand extends Command
{

    /**
     * @var \Temosh\Mongo\Client\Client
     */
    private $mongoClient;

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *  The Input object.
     * @param bool $forceNew
     *  Flag indicates that new mongo client should be created even it already exists.
     *
     * @return \Temosh\Mongo\Client\Client
     */
    public function getMongoClientFromInput(InputInterface $input, $forceNew = false)
    {
        if ($this->mongoClient === null || ((bool) $forceNew)) {
            $this->mongoClient = Client::fromUserInput($input);
        }

        return $this->mongoClient;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $definition = new InputDefinition([
            $this->hostOption(),
            $this->portOption(),
            $this->usernameOption(),
            $this->passwordOption(),
            $this->authenticationDatabaseOption(),
            $this->dbArgument(),
        ]);

        $this->setName('read')
            ->setDescription('Selects data from MongoDB collection.')
            ->setDefinition($definition);
    }

    /**
     * DB argument definition.
     *
     * @return \Symfony\Component\Console\Input\InputArgument
     */
    protected function dbArgument()
    {
        return new InputArgument(
            'db',
            InputArgument::REQUIRED,
            'database name to connect to'
        );
    }

    /**
     * Host option definition.
     *
     * @return \Symfony\Component\Console\Input\InputOption
     */
    protected function hostOption()
    {
        return new InputOption(
            'host',
            'H',
            InputOption::VALUE_OPTIONAL,
            'server to connect to',
            Options::DEFAULT_HOST
        );
    }

    /**
     * Port option definition.
     *
     * @return \Symfony\Component\Console\Input\InputOption
     */
    protected function portOption()
    {
        return new InputOption(
            'port',
            'P',
            InputOption::VALUE_OPTIONAL,
            'port to connect to',
            Options::DEFAULT_PORT
        );
    }

    /**
     * Username option definition.
     *
     * @return \Symfony\Component\Console\Input\InputOption
     */
    protected function usernameOption()
    {
        return new InputOption(
            'user',
            'u',
            InputOption::VALUE_OPTIONAL,
            'username for authentication',
            ''
        );
    }

    /**
     * Password option definition.
     *
     * @return \Symfony\Component\Console\Input\InputOption
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function passwordOption()
    {
        return new InputOption(
            'pass',
            'p',
            InputOption::VALUE_OPTIONAL,
            'password for authentication',
            ''
        );
    }

    /**
     * Authentication Database option definition.
     *
     * @return \Symfony\Component\Console\Input\InputOption
     */
    protected function authenticationDatabaseOption()
    {
        return new InputOption(
            'authenticationDatabase',
            null,
            InputOption::VALUE_OPTIONAL,
            'database for authentication'
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
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
        $authDb = trim($input->getOption('authenticationDatabase'));
        $input->setOption('authenticationDatabase', $authDb ?: null);
    }
}
