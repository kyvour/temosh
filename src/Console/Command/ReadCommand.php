<?php

namespace Temosh\Console\Command;

use MongoDB\Driver\Exception\Exception as MongoDbException;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Temosh\Console\MongoShellInterface;
use Temosh\Sql\Exception\ParseSqlException;

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
            $client = $app->getMongoClientFromInput($input);
            $client->checkConnection();
        } catch (MongoDbException $e) {
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

            // Try to parse query string.
            try {
                $sqlQueryStatement = $query->parse();
            } catch (ParseSqlException $e) {
                $output->writeln([
                    '<error>' . $e->getMessage() . '</error>',
                    '<error>' . sprintf('Required form: %s', ParseSqlException::REQUIRED_QUERY_STRUCTURE) . '</error>',
                ]);
                continue;
            } catch (\Exception $e) {
                $output->writeln([
                    '<error>Unexpected error occurred.</error>',
                    '<error>' . $e->getMessage() . '</error>',
                ]);
                continue;
            }

            // Try to build and execute MongoDB query.
            try {
                $result = $client->executeSelectStatement($sqlQueryStatement);
                $this->printResults($output, $result);
            } catch (MongoDbException $e) {
                $output->writeln('<error>' . $e->getMessage() . '</error>');
                continue;
            } catch (\Exception $e) {
                $output->writeln([
                    '<error>Unexpected error occurred.</error>',
                    '<error>' . $e->getMessage() . '</error>'
                ]);
                continue;
            }
        } while (true);
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param array $result
     */
    protected function printResults(OutputInterface $output, array $result)
    {
        if (empty($result)) {
            $output->writeln('<comment>There are no result matches your query.</comment>');
        } else {
            $table = new Table($output);
            $headers = array_keys($result[0]);
            $table->setHeaders($headers)->setRows($result)->render();
        }
    }
}
