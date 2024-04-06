<?php

namespace Octopy\L3D;

use Exception;
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
        return $this->finder->getMigrationPaths();
    }

    /**
     * @throws Exception
     */
    public function getServiceProviders() : array
    {
        return $this->finder->getServiceProviders();
    }
}