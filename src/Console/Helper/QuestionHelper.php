<?php

namespace Temosh\Console\Helper;

use Symfony\Component\Console\Helper\QuestionHelper as QuestionHelperBase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Temosh\Mongo\Connection\Options;
use Temosh\Mongo\Connection\OptionsNormalizerInterface;
use Temosh\Mongo\Connection\OptionsValidatorInterface;

/**
 * {@inheritdoc}
 */
class QuestionHelper extends QuestionHelperBase implements QuestionHelperInterface
{

    /**
     * @var \Temosh\Mongo\Connection\OptionsValidatorInterface
     */
    private $optionsValidator;

    /**
     * @var \Temosh\Mongo\Connection\OptionsNormalizerInterface
     */
    private $optionsNormalizer;

    /**
     * QuestionHelper constructor.
     *
     * @param \Temosh\Mongo\Connection\OptionsNormalizerInterface $normalizer
     * @param \Temosh\Mongo\Connection\OptionsValidatorInterface $validator
     */
    public function __construct(
        OptionsNormalizerInterface $normalizer,
        OptionsValidatorInterface $validator
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

        return (string) parent::ask($input, $output, $question);
    }

    /**
     * {@inheritdoc}
     */
    public function askHost(InputInterface $input, OutputInterface $output)
    {
        $question = new Question(
            sprintf(
                'Please enter the host address to connect to < %s >:',
                Options::DEFAULT_HOST
            ),
            Options::DEFAULT_HOST
        );
        $question
            ->setNormalizer($this->optionsNormalizer->normalizeHost())
            ->setValidator($this->optionsValidator->validateHost());

        return (string) parent::ask($input, $output, $question);
    }

    /**
     * {@inheritdoc}
     */
    public function askPort(InputInterface $input, OutputInterface $output)
    {
        $question = new Question(
            sprintf(
                'Please enter the port to connect to < %s >:',
                Options::DEFAULT_PORT
            ),
            Options::DEFAULT_PORT
        );
        $question
            ->setValidator($this->optionsNormalizer->normalizePort())
            ->setValidator($this->optionsValidator->validatePort());

        return (int) parent::ask($input, $output, $question);
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

        return (string) parent::ask($input, $output, $question);
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

        return (string) parent::ask($input, $output, $question);
    }
}
