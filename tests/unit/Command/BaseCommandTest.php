<?php

declare(strict_types=1);

namespace Temosh\Test\Command;

use Temosh\Command\ReadCommand;

/**
 * @coversDefaultClass \Temosh\Command\BaseCommand
 */
class BaseCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Temosh\Command\ReadCommand
     */
    private $command;

    /**
     * Data provider for ::requiredArguments
     *
     * @return string[]
     */
    public function requiredArgumentsProvider(): array
    {
        return [
            ['db'],
        ];
    }

    /**
     * Data provider for ::requiredOptions
     *
     * @return string[]
     */
    public function requiredOptionsProvider(): array
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
     * @covers       \Temosh\Command\BaseCommand::configure
     * @dataProvider requiredArgumentsProvider
     *
     * @param string $argName
     */
    public function requiredArguments($argName): void
    {
        $definition = $this->command->getDefinition();
        $this->assertTrue($definition->hasArgument($argName));
    }

    /**
     * @test
     * @covers       \Temosh\Command\BaseCommand::configure
     * @dataProvider requiredOptionsProvider
     *
     * @param string $optionName
     */
    public function requiredOptions($optionName): void
    {
        $definition = $this->command->getDefinition();
        $this->assertTrue($definition->hasOption($optionName));
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->command = new ReadCommand();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        $this->command = null;
    }
}
