<?php

namespace Temosh\Test\Console;

use MongoDB\Exception\InvalidArgumentException;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\HelperInterface;
use Symfony\Component\Console\Input\InputInterface;
use Temosh\Console\MongoShellInterface;
use Temosh\Console\Shell;
use Temosh\Mongo\Client\ExtendedClientInterface;
use Temosh\Mongo\Query\MongoQueryBuilderInterface;
use Temosh\Sql\Query\SqlQueryInterface;

/**
 * Class ShellTest
 *
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
    public function shellConstruct()
    {
        $this->assertInstanceOf(MongoShellInterface::class, $this->shell);
        $this->assertInstanceOf(Application::class, $this->shell);
    }

    /**
     * @test
     * @covers \Temosh\Console\Shell::getSqlQuery()
     */
    public function getSqlQuery()
    {
        $this->assertInstanceOf(SqlQueryInterface::class, $this->shell->getSqlQuery());
    }

    /**
     * @test
     * @covers \Temosh\Console\Shell::setHelper()
     */
    public function setHelper()
    {
        $mockHelper = $this->createMock(HelperInterface::class);
        $mockHelper->expects($this->once())->method('getName')->willReturn('test_table');

        $helperSet = $this->shell->setHelper($mockHelper)->getHelperSet();
        $this->assertTrue($helperSet->has('test_table'));
    }

    /**
     * @test
     * @covers \Temosh\Console\Shell::getMongoClientFromInput()
     */
    public function getMongoClientFromWrongInput1()
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
    public function getMongoClientFromInput2()
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
    protected function setUp()
    {
        parent::setUp();
        $this->shell = new Shell();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        parent::tearDown();
        $this->shell = null;
    }
}
