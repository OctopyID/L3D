<?php

namespace Octopy\L3D;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Octopy\L3D\Cache\Cache;
use Octopy\L3D\Finder\Finder;

class Domain
{
    /**
     * @var Finder
     */
    protected Finder $finder;

    /**
     * @param  string $path
     */
    public function __construct(string $path)
    {
        $this->finder = new Finder($path);
    }

    /**
     * @param  string|null $domain
     * @return string
     */
    public function path(string $domain = null) : string
    {
        $path = config('domain.path');

        if ($domain) {
            $path .= '/' . $domain;
        }

        return $path;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getDomainNames() : array
    {
        return $this->finder->getDomains()->keys()->toArray();
    }

    /**
     * @throws Exception
     */
    public function getMigrationPaths() : array
    {
        return $this->getFilteredDomains('migration')->map(fn($domain) : string => $domain['migration'])->toArray();
    }

    /**
     * @throws Exception
     */
    public function getServiceProviders() : array
    {
        return $this->getFilteredDomains('providers')->flatMap(fn($domain) : array => $domain['providers'])->toArray();
    }

    /**
     * @param  string $type
     * @return Collection
     * @throws Exception
     */
    private function getFilteredDomains(string $type) : Collection
    {
        return $this->finder->getDomains()->filter(fn($domain) => ! is_null($domain[$type]));
    }
}