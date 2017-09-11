<?php

namespace Temosh\Test\Mongo\Connection;

use Symfony\Component\Console\Exception\InvalidArgumentException;
use Temosh\Mongo\Connection\ConnectionOptionsValidator;

class ConnectionOptionsValidatorTest extends \PHPUnit_Framework_TestCase
{

    private $validator;

    protected function setUp()
    {
        parent::setUp();

        $this->validator = new ConnectionOptionsValidator();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->validator = null;
    }

    public function testValidateHost()
    {
        $callable = $this->validator->validateHost();
        $this->assertTrue(is_callable($callable));
        $this->assertEquals('127.0.0.1', $callable('127.0.0.1'));
    }

    /**
     * Data provider for self::testValidateHostBad
     */
    public function validateHostBadDataProvider()
    {
        return [
            [null],
            [false],
            [[]],
            [42],
            [new \stdClass()],
            [''],
        ];
    }

    /**
     * @dataProvider validateHostBadDataProvider
     */
    public function testValidateHostBad($host)
    {
        $callable = $this->validator->validateHost();
        $this->expectException(InvalidArgumentException::class);
        $this->assertEquals($host, $callable($host));
    }

    public function testValidatePort()
    {
        $callable = $this->validator->validatePort();
        $this->assertTrue(is_callable($callable));
        $this->assertEquals(1, $callable(1));
        $this->assertEquals(65345, $callable(65345));
    }

    /**
     * Data provider for self::testValidatePortBad
     */
    public function validatePortBadDataProvider()
    {
        return [
            [null],
            [false],
            [0],
            ['test'],
            [65346],
        ];
    }

    /**
     * @dataProvider validatePortBadDataProvider
     */
    public function testValidatePortBad($port)
    {
        $callable = $this->validator->validatePort();
        $this->expectException(InvalidArgumentException::class);
        $this->assertEquals($port, $callable($port));
    }
}
