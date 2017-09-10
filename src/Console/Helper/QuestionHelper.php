<?php

namespace Temosh\Console\Helper;

use Symfony\Component\Console\Helper\QuestionHelper as QuestionHelperBase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Temosh\Mongo\Connection\ConnectionOptions;
use Temosh\Mongo\Connection\ConnectionOptionsNormalizerInterface;
use Temosh\Mongo\Connection\ConnectionOptionsValidatorInterface;

/**
 * {@inheritdoc}
 */
class QuestionHelper extends QuestionHelperBase implements QuestionHelperInterface
{

    /**
     * @var \Temosh\Mongo\Connection\ConnectionOptionsValidatorInterface
     */
    private $optionsValidator;

    /**
     * @var \Temosh\Mongo\Connection\ConnectionOptionsNormalizerInterface
     */
    private $optionsNormalizer;

    /**
     * QuestionHelper constructor.
     *
     * @param \Temosh\Mongo\Connection\ConnectionOptionsNormalizerInterface $normalizer
     * @param \Temosh\Mongo\Connection\ConnectionOptionsValidatorInterface $validator
     */
    public function __construct(
        ConnectionOptionsNormalizerInterface $normalizer,
        ConnectionOptionsValidatorInterface $validator
    ) {
        $this->optionsNormalizer = $normalizer;
        $this->optionsValidator = $validator;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'temosh_question';
    }

    /**
     * {@inheritdoc}
     */
    public function askDbName(InputInterface $input, OutputInterface $output)
    {
        $question = new Question('Please enter the database to connect to:');
        $question
            ->setNormalizer($this->optionsNormalizer->normalizeDbName())
            ->setValidator($this->optionsValidator->validateDbName())
            ->setMaxAttempts(null);

        return (string) $this->ask($input, $output, $question);
    }

    /**
     * {@inheritdoc}
     */
    public function askHost(InputInterface $input, OutputInterface $output)
    {
        $question = new Question(
            sprintf(
                'Please enter the host address to connect to < %s >:',
                ConnectionOptions::DEFAULT_HOST
            ),
            ConnectionOptions::DEFAULT_HOST
        );
        $question
            ->setNormalizer($this->optionsNormalizer->normalizeHost())
            ->setValidator($this->optionsValidator->validateHost());

        return (string) $this->ask($input, $output, $question);
    }

    /**
     * {@inheritdoc}
     */
    public function askPort(InputInterface $input, OutputInterface $output)
    {
        $question = new Question(
            sprintf(
                'Please enter the port to connect to < %s >:',
                ConnectionOptions::DEFAULT_PORT
            ),
            ConnectionOptions::DEFAULT_PORT
        );
        $question
            ->setValidator($this->optionsNormalizer->normalizePort())
            ->setValidator($this->optionsValidator->validatePort());

        return (int) $this->ask($input, $output, $question);
    }

    /**
     * {@inheritdoc}
     */
    public function askUser(InputInterface $input, OutputInterface $output)
    {
        $question = new Question('Please enter an username for the authentication:');
        $question
            ->setNormalizer($this->optionsNormalizer->normalizeUser())
            ->setValidator($this->optionsValidator->validateUser());

        return (string) $this->ask($input, $output, $question);
    }

    /**
     * {@inheritdoc}
     */
    public function askPass(InputInterface $input, OutputInterface $output)
    {
        $question = new Question('Please enter a password for the authentication:');
        $question
            ->setValidator($this->optionsValidator->validatePass())
            ->setHidden(true)
            ->setHiddenFallback(true);

        return (string) $this->ask($input, $output, $question);
    }

    /**
     * {@inheritdoc}
     */
    public function askQuery(InputInterface $input, OutputInterface $output)
    {
        $question = new Question('Query: ');

        return (string) $this->ask($input, $output, $question);
    }
}
