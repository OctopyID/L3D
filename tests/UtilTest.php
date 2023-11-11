<?php

namespace Octopy\Tests;

use Octopy\L3D\Util;

class UtilTest extends TestCase
{
    /**
     * @test
     */
    public function getClass() : void
    {
        $this->assertSame('App\\Http\\Controllers\\HomeController', Util::getClass('/Http/Controllers/HomeController.php'));
    }
}