<?php

declare(strict_types=1);

namespace Temosh\Test;

use Temosh\Boot\BootLoader;

/**
 * @coversDefaultClass \Temosh\Boot\BootLoader
 */
class BootLoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @covers \Temosh\Boot\BootLoader::boot()
     *
     * @return void
     */
    public function boot(): void
    {
        $appDir = dirname(__DIR__, 2);
        $existingClass = self::class;
        $nonExistingClass = '\Temosh\Test\Boot\Dummy\NonExisting\ClassName';

        $this->assertTrue(BootLoader::boot($appDir, $existingClass));
        $this->assertFalse(BootLoader::boot($appDir, $nonExistingClass));
    }
}
