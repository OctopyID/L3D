<?php

namespace Octopy\L3D;

if (! function_exists('domain')) {
    /**
     * @param  string $name
     * @return Domain
     */
    function domain(string $name) : Domain
    {
        return new Domain($name);
    }
}

if (! function_exists('domain_path')) {
    /**
     * @param  string $path
     * @return string
     */
    function domain_path(string $path) : string
    {
        return str(config('domain.path') . '/' . $path)->deduplicate('/');
    }
}
