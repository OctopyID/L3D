<?php

namespace Octopy\L3D\Finder;

use Illuminate\Support\ServiceProvider;
use Octopy\L3D\DomainInfo;
use Octopy\L3D\Support\Util;
use Symfony\Component\Finder\Finder as SymfonyFinder;

class Finder
{
    protected SymfonyFinder $finder;

    /**
     * Domain constructor.
     */
    public function __construct(protected string $location)
    {
        $this->finder = new SymfonyFinder;
    }

    /**
     * @return DomainInfo[]
     */
    public function findDomains() : array
    {
        if (! is_dir($this->location)) {
            return [];
        }

        $domains = [];
        foreach ($this->finder->in($this->location)->directories()->depth(0) as $domain) {
            $domains[] = new DomainInfo($domain->getFilename());
        }

        return $domains;
    }

    /**
     * @return array
     */
    public function findProviders() : array
    {
        $providers = [];
        foreach ($this->finder->in($this->location)->files() as $provider) {
            $provider = Util::getClass($provider);
            if (is_subclass_of($provider, ServiceProvider::class)) {
                $providers[] = $provider;
            }
        }

        return $providers;
    }
}
