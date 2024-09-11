<?php

namespace Octopy\Tests;

use Octopy\L3D\Util;
use PHPUnit\Framework\Attributes\Test;

class UtilTest extends TestCase
{
    #[Test]
    public function getClassShouldReturnFullyQualifiedClassName() : void
    {
        $this->assertSame('App\\Http\\Controllers\\HomeController', Util::getClass(
            app_path('Http/Controllers/HomeController.php')
        ));
    }
}
