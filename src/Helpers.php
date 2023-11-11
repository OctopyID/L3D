<?php

/**
 * @param  string|null $path
 * @return string
 */
function domain_path(string $path = null) : string
{
    return \Octopy\L3D\Support\Facades\Domain::path($path);
}