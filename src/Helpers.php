<?php

namespace Octopy\L3D;

/**
 * @param  string $name
 * @return Domain
 */
function domain(string $name) : Domain
{
    return new Domain($name);
}

/**
 * @param  string $path
 * @return string
 */
function domain_path(string $path) : string
{
    return config('domain.path') . '/' . $path;
}

