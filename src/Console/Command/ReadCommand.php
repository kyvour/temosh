<?php

namespace Temosh\Console\Command;

use MongoDB\Driver\Exception\Exception;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Temosh\Console\MongoShellInterface;

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
        /** @var \Temosh\Console\MongoShellInterface $app */
        $app = $this->getApplication();
        if (!($app instanceof MongoShellInterface)) {
            throw new LogicException('Application instance should implement MongoShellInterface');
        }

        /** @var \Temosh\Console\Helper\QuestionHelperInterface $helper */
        $helper = $this->getHelper('temosh_question');

        // Check connection to the MongoDB database.
        try {
            $client = $this->getMongoClientFromInput($input);
            $client->checkConnection();
        } catch (Exception $e) {
            $output->writeln([
                '<error>Connection to the database failed.</error>',
                '<error>' . $e->getMessage() . '</error>',
            ]);

            return;
        }

        // Infinite loop of user queries.
        do {
            // Get the sql query from user input.
            $queryString = $helper->askQuery($input, $output);
            $query = $app->getSqlQuery()->setQueryString($queryString);

            // Do nothing if input is empty.
            if ($query->isEmpty()) {
                continue;
            }

            // Exit on "exit" query.
            if ($query->isExitCommand()) {
                return;
            }

            try {
                $sqlQueryArray = $query->parse();
                // Execute query.
                $output->writeln(var_dump($sqlQueryArray));
            } catch (\Exception $e) {
                // Notify user about exception (invalid query, bad syntax etc.)
                $output->writeln('<error>' . $e->getMessage() . '</error>');
            }
        } while (true);
    }
}
