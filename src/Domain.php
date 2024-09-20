<?php

namespace Octopy\L3D;

use Octopy\L3D\Finder\Finder;

class Domain
{
    protected Finder $finder;

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
        return $this->finder->findDomains();
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
}
