<?php

namespace Octopy\L3D\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static getDomainNames()
 * @method static path(string|null $path)
 */
class Domain extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor() : string
    {
        return \Octopy\L3D\Domain::class;
    }
}