<?php

namespace Temosh\Test\Mongo\Connection;

use Temosh\Mongo\Connection\ConnectionOptionsNormalizer;

/**
 * Class ConnectionOptionsNormalizerTest
 *
 * @coversDefaultClass \Temosh\Mongo\Connection\ConnectionOptionsNormalizer
 */
class ConnectionOptionsNormalizerTest extends \PHPUnit_Framework_TestCase
{
    private $normalizer;

    /**
     * Data provider for ::testNormalizeString
     */
    public function normalizeStringDataProvider()
    {
        return [
            [null, ''],
            [false, ''],
            ['', ''],
            ['test', 'test'],
            ['test2 ', 'test2'],
            [' test3', 'test3'],
        ];
    }

    /**
     * @test
     * @covers       \Temosh\Mongo\Connection\ConnectionOptionsNormalizer::normalizeString()
     * @dataProvider normalizeStringDataProvider
     */
    public function normalizeString($input, $expected)
    {
        $class = new \ReflectionClass($this->normalizer);
        $method = $class->getMethod('normalizeString');
        $method->setAccessible(true);
        $normalizerCallable = $method->invokeArgs($this->normalizer, []);

        $this->assertTrue(is_callable($normalizerCallable));
        $this->assertEquals($expected, $normalizerCallable($input));
    }

    /**
     * @test
     * @covers       \Temosh\Mongo\Connection\ConnectionOptionsNormalizer::normalizeHost()
     * @dataProvider normalizeStringDataProvider
     */
    public function normalizeHost($input, $expected)
    {
        $normalizerCallable = $this->normalizer->normalizeHost();
        $this->assertTrue(is_callable($normalizerCallable));
        $this->assertEquals($expected, $normalizerCallable($input));
    }

    /**
     * @test
     * @covers       \Temosh\Mongo\Connection\ConnectionOptionsNormalizer::normalizeDbName()
     * @dataProvider normalizeStringDataProvider
     */
    public function normalizeDbName($input, $expected)
    {
        $normalizerCallable = $this->normalizer->normalizeDbName();
        $this->assertTrue(is_callable($normalizerCallable));
        $this->assertEquals($expected, $normalizerCallable($input));
    }

    /**
     * @test
     * @covers       \Temosh\Mongo\Connection\ConnectionOptionsNormalizer::normalizeUser()
     * @dataProvider normalizeStringDataProvider
     */
    public function normalizeUser($input, $expected)
    {
        $normalizerCallable = $this->normalizer->normalizeUser();
        $this->assertTrue(is_callable($normalizerCallable));
        $this->assertEquals($expected, $normalizerCallable($input));
    }

    /**
     * Data provider for ::testNormalizeInteger
     */
    public function normalizeIntegerDataProvider()
    {
        return [
            [null, 0],
            [false, 0],
            ['', 0],
            ['test', 0],
            ['42 ', '42'],
            [' 84', '84'],
        ];
    }

    /**
     * @test
     * @covers       \Temosh\Mongo\Connection\ConnectionOptionsNormalizer::normalizeInteger()
     * @dataProvider normalizeIntegerDataProvider
     */
    public function normalizeInteger($input, $expected)
    {
        $class = new \ReflectionClass($this->normalizer);
        $method = $class->getMethod('normalizeInteger');
        $method->setAccessible(true);
        $normalizerCallable = $method->invokeArgs($this->normalizer, []);

        $this->assertTrue(is_callable($normalizerCallable));
        $this->assertEquals($expected, $normalizerCallable($input));
    }

    /**
     * @test
     * @covers       \Temosh\Mongo\Connection\ConnectionOptionsNormalizer::normalizePort()
     * @dataProvider normalizeIntegerDataProvider
     */
    public function normalizePort($input, $expected)
    {
        $normalizerCallable = $this->normalizer->normalizePort();
        $this->assertTrue(is_callable($normalizerCallable));
        $this->assertEquals($expected, $normalizerCallable($input));
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->normalizer = new ConnectionOptionsNormalizer();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->normalizer = null;
    }
}
