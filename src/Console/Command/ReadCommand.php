<?php

namespace Temosh\Console\Command;

use MongoDB\Driver\Exception\Exception;
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

        /** @var \Temosh\Console\Helper\QuestionHelperInterface $helper */
        $helper = $this->getHelper('temosh_question');

        // Infinite loop of user queries.
        do {
            try {
                // Get the sql query.
                $query = $helper->askQuery($input, $output);

                // Exit on "exit" query.
                // @todo move this condition to separate class.
                if (rtrim(trim($query), '();') === 'exit') {
                    return;
                }

                // Execute query.
                $output->writeln('Dummy output...');
            } catch (\Exception $e) {
                // Notify user about exception (invalid query, bad syntax etc.)
                $output->writeln('<error>' . $e->getMessage() . '</error>');
            }

        } while (true);

    }
}
