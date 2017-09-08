<?php

namespace Temosh\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SelectCommand extends Command
{

    protected function configure()
    {
        $this->setName('select')
          ->setDescription('Selects data from MongoDB collection.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Dummy output...');
    }
}
