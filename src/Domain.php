<?php

namespace Octopy\L3D;

use Octopy\L3D\Finder\Finder;

class Domain
{
    protected Finder $finder;

    /**
     * @var array
     */
    protected array $domains = [];

    /**
     * Domain constructor
     */
    public function __construct()
    {
        $this->finder = new Finder(config('domain.path'));
    }

    /**
     * @return array
     */
    public function domains() : array
    {
        if (empty($this->domains)) {
            $this->domains = $this->finder->findDomains();
        }

        return $this->domains;
    }

    /**
     * @return array
     */
    public function providers() : array
    {
        $providers = [];

        collect($this->domains())->each(function (DomainInfo $domain) use (&$providers) {
            $providers = array_merge($providers, $domain->providers());
        });

        return $providers;
    }

    /**
     * @return array
     */
    public function migrations() : array
    {
        $migrations = [];

        collect($this->domains())->each(function (DomainInfo $domain) use (&$migrations) {
            $migrations[] = $domain->path('Database/Migrations');
        });

        return $migrations;
    }
}
