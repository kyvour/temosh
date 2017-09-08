<?php

namespace Temosh\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SelectCommand
 *
 * The command for retrieving dta from MongoDB.
 */
class SelectCommand extends Command
{

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $definition = new InputDefinition([
            $this->hostOption(),
            $this->portOption(),
            $this->usernameOption(),
            $this->passwordOption(),
            $this->authenticationDatabaseOption(),
            $this->dbArgument()
        ]);

        $this->setName('select')
          ->setDescription('Selects data from MongoDB collection.')
          ->setDefinition($definition);
    }

    /**
     * DB argument definition.
     *
     * @return \Symfony\Component\Console\Input\InputArgument
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function dbArgument() {
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
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function hostOption() {
        return new InputOption(
          'host',
          'H',
          InputOption::VALUE_REQUIRED,
          'server to connect to',
          '127.0.0.1'
        );
    }

    /**
     * Port option definition.
     *
     * @return \Symfony\Component\Console\Input\InputOption
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function portOption() {
        return new InputOption(
          'port',
          'P',
          InputOption::VALUE_REQUIRED,
          'port to connect to',
          '27017'
        );
    }

    /**
     * Username option definition.
     *
     * @return \Symfony\Component\Console\Input\InputOption
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function usernameOption() {
        return new InputOption(
          'username',
          'u',
          InputOption::VALUE_OPTIONAL,
          'username for authentication'
        );
    }

    /**
     * Password option definition.
     *
     * @return \Symfony\Component\Console\Input\InputOption
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function passwordOption() {
        return new InputOption(
          'password',
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
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function authenticationDatabaseOption() {
        return new InputOption(
          'authenticationDatabase',
          null,
          InputOption::VALUE_REQUIRED,
          'user source (defaults to db name)'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Dummy output...');
    }
}
