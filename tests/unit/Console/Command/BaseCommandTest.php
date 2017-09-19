<?php

namespace Temosh\Test\Console\Command;

use Temosh\Console\Command\ReadCommand;

/**
 * @coversDefaultClass \Temosh\Console\Command\BaseCommand
 */
class BaseCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Temosh\Console\Command\ReadCommand
     */
    private $command;

    /**
     * Data provider for ::requiredArguments
     *
     * @return array
     */
    public function requiredArgumentsProvider()
    {
        return [
            ['db'],
        ];
    }

    /**
     * Data provider for ::requiredOptions
     *
     * @return array
     */
    public function requiredOptionsProvider()
    {
        return [
            ['host'],
            ['port'],
            ['user'],
            ['pass'],
            ['authenticationDatabase'],
        ];
    }

    /**
     * @test
     * @covers       \Temosh\Console\Command\BaseCommand::configure
     * @dataProvider requiredArgumentsProvider
     */
    public function requiredArguments($argName)
    {
        $definition = $this->command->getDefinition();
        $this->assertTrue($definition->hasArgument($argName));
    }

    /**
     * @test
     * @covers       \Temosh\Console\Command\BaseCommand::configure
     * @dataProvider requiredOptionsProvider
     */
    public function requiredOptions($optionName)
    {
        $definition = $this->command->getDefinition();
        $this->assertTrue($definition->hasOption($optionName));
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();
        $this->command = new ReadCommand();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        parent::tearDown();
        $this->command = null;
    }
}