<?php

namespace Octopy\L3D;

use Illuminate\Contracts\Support\Arrayable;

class Domain implements Arrayable
{
    /**
     * @var array
     */
    public array $providers = [];

    /**
     * @var string
     */
    public string $migration;

    /**
     * @param  string $namespace
     * @param  string $realpath
     */
    public function __construct(public string $namespace, public string $realpath)
    {
        $this->migration = $this->basepath('Database/Migrations');
    }

    /**
     * @param  string $path
     * @return string
     */
    public function basepath(string $path) : string
    {
        return $this->realpath . '/' . $path;
    }

    /**
     * @param  string|null $namespace
     * @return string
     */
    public function namespace(string|null $namespace = null) : string
    {
        if (is_null($namespace)) {
            return $this->namespace;
        }

        return $this->namespace . '\\' . $namespace;
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        return [
            'realpath'  => $this->realpath,
            'namespace' => $this->namespace,
            'providers' => $this->providers,
            'migration' => $this->migration,
        ];
    }
}
