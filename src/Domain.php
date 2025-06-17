<?php

namespace Octopy\L3D;

use Illuminate\Contracts\Support\Arrayable;

class Domain implements Arrayable
{
    /**
     * @var array
     */
    public array $policies = [];

    /**
     * @var array
     */
    public array $providers = [];

    /**
     * @var string|null
     */
    public string|null $migration;

    /**
     * @param  string|null $namespace
     * @param  string|null $realpath
     */
    public function __construct(public string|null $namespace = null, public string|null $realpath = null)
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
     * @param  array $array
     * @return $this
     */
    public function fromArray(array $array) : Domain
    {
        foreach ($array as $key => $value) {
            $this->{$key} = $value;
        }

        return $this;
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
            'policies'  => $this->policies,
            'migration' => $this->migration,
        ];
    }
}
