<?php

namespace Temosh\Test\Mongo\Connection;

use Symfony\Component\Console\Exception\InvalidArgumentException;
use Temosh\Mongo\Connection\ConnectionOptionsValidator;

/**
 * @coversDefaultClass \Temosh\Mongo\Connection\ConnectionOptionsValidator
 */
class ConnectionOptionsValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Temosh\Mongo\Connection\ConnectionOptionsValidator
     */
    private $validator;

    /**
     * @test
     * @covers \Temosh\Mongo\Connection\ConnectionOptionsValidator::validateHost()
     */
    public function validateHost()
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
     * @test
     * @covers       \Temosh\Mongo\Connection\ConnectionOptionsValidator::validateHost()
     * @dataProvider validateHostBadDataProvider
     */
    public function validateHostBad($host)
    {
        $callable = $this->validator->validateHost();
        $this->expectException(InvalidArgumentException::class);
        $this->assertEquals($host, $callable($host));
    }

    /**
     * @test
     * @covers \Temosh\Mongo\Connection\ConnectionOptionsValidator::validatePort()
     */
    public function validatePort()
    {
        $callable = $this->validator->validatePort();
        $this->assertTrue(is_callable($callable));
        $this->assertEquals(1, $callable(1));
        $this->assertEquals(65345, $callable(65345));
    }

    /**
     * Data provider for ::testValidatePortBad
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
     * @test
     * @covers       \Temosh\Mongo\Connection\ConnectionOptionsValidator::validatePort()
     * @dataProvider validatePortBadDataProvider
     */
    public function validatePortBad($port)
    {
        $callable = $this->validator->validatePort();
        $this->expectException(InvalidArgumentException::class);
        $this->assertEquals($port, $callable($port));
    }

    /**
     * {@inheritdoc}
     */
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
}
