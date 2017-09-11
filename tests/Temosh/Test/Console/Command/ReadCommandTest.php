<?php

namespace Temosh\Test\Console\Command;

use Temosh\Console\Command\ReadCommand;

/**
 * @coversDefaultClass \Temosh\Console\Command\SelectCommand
 * @group Temosh
 */
class ReadCommandTest extends \PHPUnit_Framework_TestCase
{

    private $command;

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

    /**
     * Data provider for testRequiredArguments
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
     * Data provider for testRequiredArguments
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
     * Tests for required arguments for SelectCommand.
     *
     * @dataProvider requiredArgumentsProvider
     *
     * @param $argName
     *  The argument name.
     */
    public function testRequiredArguments($argName)
    {
        $definition = $this->command->getDefinition();
        $this->assertTrue($definition->hasArgument($argName));
    }

    /**
     * Tests for required arguments for SelectCommand.
     *
     * @dataProvider requiredOptionsProvider
     *
     * @param $optionName
     *  The option name.
     */
    public function testRequiredOptions($optionName)
    {
        $definition = $this->command->getDefinition();
        $this->assertTrue($definition->hasOption($optionName));
    }
}
