<?php

namespace Octopy\L3D;

use Illuminate\Support\Facades\App;

final class Util
{
    /**
     * @param  mixed $file
     * @return string
     */
    public static function getClass(mixed $file) : string
    {
        if ($file instanceof SplFileInfo) {
            $file = $file->getPathname();
        }

        return str($file)->replace(App::path(), '')->replace('.php', '')->replace('/', '\\')->trim('\\')->prepend(App::getNamespace());
    }
}