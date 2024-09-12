<?php

namespace Octopy\Tests;

use Octopy\L3D\Util;

it('convert path to class name', function () {
    expect('App\\Http\\Controllers\\HomeController')->toBe(Util::getClass(
        app_path('Http/Controllers/HomeController.php')
    ));
});
