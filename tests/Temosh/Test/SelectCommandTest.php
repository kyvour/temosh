<?php

namespace Temosh\Test;

use Temosh\Console\Command\SelectCommand;

/**
 * @coversDefaultClass \Temosh\Console\Command\SelectCommand
 * @group Temosh
 */
class SelectCommandTest extends \PHPUnit_Framework_TestCase
{

    private $command;

    protected function setUp()
    {
        parent::setUp();

        $this->command = new SelectCommand();
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->command = null;
    }

    public function requiredArgumentsProvider()
    {
        return [
          ['db'],
        ];
    }

    public function requiredOptionsProvider()
    {
        return [
          ['host'],
          ['port'],
          ['username'],
          ['password'],
          ['authenticationDatabase'],
        ];
    }

    /**
     * Tests for required arguments for SelectCommand.
     *
     * @dataProvider requiredArgumentsProvider
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
     */
    public function testSelectCommand($optionName)
    {
        $definition = $this->command->getDefinition();
        $this->assertTrue($definition->hasOption($optionName));
    }
}
