<?php

namespace Temosh\Test\Console;

use MongoDB\Exception\InvalidArgumentException;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\HelperInterface;
use Symfony\Component\Console\Input\ArgvInput;
use Temosh\Console\MongoShellInterface;
use Temosh\Console\Shell;
use Temosh\Mongo\Client\Client;
use Temosh\Sql\Query\SqlQuery;

class ShellTest extends \PHPUnit_Framework_TestCase
{

    private $shell;

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

    public function testShell()
    {
        $this->assertInstanceOf(MongoShellInterface::class, $this->shell);
        $this->assertInstanceOf(Application::class, $this->shell);
    }

    public function testGetSqlQuery()
    {
        $this->assertInstanceOf(SqlQuery::class, $this->shell->getSqlQuery());
    }

    public function testGetDefaultHelperSet()
    {
        $this->assertTrue($this->shell->getHelperSet()->has('temosh_question'));
    }

    public function testGetMongoClientFromWrongInput()
    {
        $input = new ArgvInput();
        $this->expectException(InvalidArgumentException::class);
        $this->assertInstanceOf(Client::class, $this->shell->getMongoClientFromInput($input));
    }
}
