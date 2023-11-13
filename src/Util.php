<?php

namespace Octopy\L3D;

use Illuminate\Support\Facades\App;

final class Util
{
    /**
     * @param  string $file
     * @return string
     */
    public static function getClass(string $file) : string
    {
        return str($file)->replace(App::path(), '')->replace('.php', '')->replace('/', '\\')->trim('\\')->prepend(App::getNamespace());
    }
}