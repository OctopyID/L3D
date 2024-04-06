<?php

namespace Octopy\L3D\Console\Commands\Concerns;

trait HasGetPath
{
    /**
     * @param  string $name
     * @return string
     */
    protected function getPath($name) : string
    {
        return app_path(str_replace('\\', '/', str_replace($this->laravel->getNamespace(), '', $name)) . '.php');
    }
}