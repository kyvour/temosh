<?php

namespace Temosh\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SelectCommand
 *
 * The command for retrieving dta from MongoDB.
 */
class ReadCommand extends BaseCommand
{
    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Dummy output...');
    }
}
