<?php

declare(strict_types=1);

namespace Temosh\Test\Console;

use MongoDB\Exception\InvalidArgumentException;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Temosh\Console\MongoShellInterface;
use Temosh\Console\Shell;
use Temosh\Mongo\Client\ExtendedClientInterface;
use Temosh\Mongo\Query\MongoQueryBuilderInterface;
use Temosh\Sql\Query\SqlQueryInterface;

/**
 * @coversDefaultClass \Temosh\Console\Shell
 */
class ShellTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Temosh\Console\Shell
     */
    private $shell;

    /**
     * @test
     * @covers \Temosh\Console\Shell::__construct()
     */
    public function shellConstruct(): void
    {
        $this->assertInstanceOf(MongoShellInterface::class, $this->shell);
        $this->assertInstanceOf(Application::class, $this->shell);
    }

    /**
     * @test
     * @covers \Temosh\Console\Shell::getSqlQuery()
     */
    public function getSqlQuery(): void
    {
        $this->assertInstanceOf(SqlQueryInterface::class, $this->shell->getSqlQuery());
    }

    /**
     * @test
     * @covers \Temosh\Console\Shell::getMongoClientFromInput()
     */
    public function getMongoClientFromWrongInput1(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $mockInput = $this->createMock(InputInterface::class);
        $mockInput->method('getArguments')->willReturn([]);
        $mockInput->method('getOptions')->willReturn([]);

        $client = $this->shell->getMongoClientFromInput($mockInput);
        $this->assertInstanceOf(ExtendedClientInterface::class, $client);
    }

    /**
     * @test
     * @covers \Temosh\Console\Shell::getMongoClientFromInput()
     */
    public function getMongoClientFromInput2(): void
    {
        $mockInput = $this->createMock(InputInterface::class);
        $mockInput->method('getArguments')->willReturn(['db' => 'test']);
        $mockInput->method('getOptions')->willReturn([]);

        $client = $this->shell->getMongoClientFromInput($mockInput);

        $this->assertInstanceOf(ExtendedClientInterface::class, $client);
        $this->assertInstanceOf(MongoQueryBuilderInterface::class, $client->getBuilder());
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->shell = new Shell();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        $this->shell = null;
    }
}
