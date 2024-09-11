<?php

namespace Octopy\L3D;

use Illuminate\Support\Facades\App;

class Util
{
    /**
     * @param  string $path
     * @return string
     */
    public static function getClass(string $path) : string
    {
        return str($path)->remove('.php')->remove(app()->path('/'))->prepend(app()->getNamespace())->replace('/', '\\');
    }
}
