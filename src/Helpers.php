<?php

namespace Octopy\L3D;

if (! function_exists('domain')) {
    /**
     * @param  string $name
     * @return DomainInfo
     */
    function domain(string $name) : DomainInfo
    {
        return new DomainInfo($name);
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
