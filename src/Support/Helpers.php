<?php

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\App;
use Octopy\L3D\L3D;

if (! function_exists('l3d')) {
    /**
     * @return L3D
     * @throws BindingResolutionException
     */
    function l3d() : L3D
    {
        return App::make(L3D::class);
    }
}
