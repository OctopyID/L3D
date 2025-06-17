<?php

namespace Octopy\L3D;

use Closure;
use Octopy\L3D\Exceptions\L3DCacheException;
use Octopy\L3D\Filesystem\Cache;
use Octopy\L3D\Filesystem\Finder;

class L3D
{
    /**
     * @var array
     */
    protected array $psr4 = [];

    /**
     * @var array
     */
    protected array $domains = [];

    /**
     * @var Cache
     */
    protected Cache $cache {
        get => new Cache();
    }

    /**
     * @param  array $psr4
     */
    public function __construct(array $psr4 = [])
    {
        $this->register($psr4);
    }

    /**
     * @param  array $psr4
     * @return $this
     */
    public function register(array $psr4) : self
    {
        // normalize the location
        foreach ($psr4 as $namespace => $location) {
            $psr4[$namespace] = rtrim($location, '/');
        }

        $this->psr4 = array_merge($this->psr4, $psr4);

        return $this;
    }

    /**
     * @param  string $name
     * @return Domain
     */
    public function domain(string $name) : Domain
    {
        return $this->domains[$name];
    }

    /**
     * @return Domain[]
     */
    public function domains() : array
    {
        return $this->domains;
    }

    /**
     * @return array
     */
    public function policies() : array
    {
        $policies = [];
        foreach ($this->domains() as $domain) {
            $policies = array_merge($policies, $domain->policies);
        }

        return $policies;
    }

    /**
     * @return array
     */
    public function providers() : array
    {
        $providers = [];
        foreach ($this->domains() as $domain) {
            $providers = array_merge($providers, $domain->providers);
        }

        return $providers;
    }

    /**
     * @param  bool $strict
     * @return array
     */
    public function migrations(bool $strict = false) : array
    {
        $migrations = [];
        foreach ($this->domains() as $domain) {
            // when strict, then make sure the directory is available
            if ($strict && ! is_dir($domain->migration)) {
                continue;
            }

            $migrations[] = $domain->migration;
        }

        return $migrations;
    }

    /**
     * @param  Closure $callback
     * @return void
     * @throws L3DCacheException
     */
    public function bootstrap(Closure $callback) : void
    {
        // load domains from cache if exists
        if ($this->cache->exists()) {
            $this->domains = $this->cache->get();
        } else {
            // try to find domains within the registered namespaces
            foreach ($this->psr4 as $namespace => $location) {
                if (! is_dir($location)) {
                    continue;
                }

                $finder = new Finder($namespace, $location);

                $this->domains = array_merge(
                    $this->domains, $finder->domains(),
                );
            }
        }

        $callback($this);
    }
}
