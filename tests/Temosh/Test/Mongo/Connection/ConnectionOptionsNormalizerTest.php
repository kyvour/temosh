<?php

namespace Temosh\Test\Mongo\Connection;

use Temosh\Mongo\Connection\ConnectionOptionsNormalizer;

class ConnectionOptionsNormalizerTest extends \PHPUnit_Framework_TestCase
{

    private $normalizer;

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

    /**
     * Data provider for self::testNormalizeString
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
     * @dataProvider normalizeStringDataProvider
     */
    public function testNormalizeHost($input, $expected)
    {
        $normalizerCallable = $this->normalizer->normalizeHost();
        $this->assertTrue(is_callable($normalizerCallable));
        $this->assertEquals($expected, $normalizerCallable($input));
    }

    /**
     * Data provider for self::testNormalizeInteger
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
     * @dataProvider normalizeIntegerDataProvider
     */
    public function testNormalizePort($input, $expected)
    {
        $normalizerCallable = $this->normalizer->normalizePort();
        $this->assertTrue(is_callable($normalizerCallable));
        $this->assertEquals($expected, $normalizerCallable($input));
    }

    /**
     * @dataProvider normalizeStringDataProvider
     */
    public function testNormalizeDbName($input, $expected)
    {
        $normalizerCallable = $this->normalizer->normalizeDbName();
        $this->assertTrue(is_callable($normalizerCallable));
        $this->assertEquals($expected, $normalizerCallable($input));
    }

    /**
     * @dataProvider normalizeStringDataProvider
     */
    public function testNormalizeUser($input, $expected)
    {
        $normalizerCallable = $this->normalizer->normalizeUser();
        $this->assertTrue(is_callable($normalizerCallable));
        $this->assertEquals($expected, $normalizerCallable($input));
    }
}
