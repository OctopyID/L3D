<?php

/**
 * @param  string|null $path
 * @return string
 */
function domain_path(string $path = null) : string
{
    return \Octopy\L3D\Support\Facades\Domain::path($path);
}

/**
 * @return \Octopy\L3D\Domain
 */
function domain() : \Octopy\L3D\Domain
{
    return \Illuminate\Support\Facades\App::make('domain');
}