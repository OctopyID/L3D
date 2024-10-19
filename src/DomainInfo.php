<?php

namespace Octopy\L3D;

use Illuminate\Support\ServiceProvider;
use Octopy\L3D\Finder\Finder;
use SplFileInfo;

class DomainInfo
{
    /**
     * @param  string $domain
     */
    public function __construct(protected string $domain)
    {
        //
    }

    /**
     * @return string
     */
    public function name() : string
    {
        return $this->domain;
    }

    /**
     * @param  string|null $path
     * @return string
     */
    public function path(string $path = null) : string
    {
        return rtrim(domain_path($this->domain . '/' . $path), '/');
    }

    /**
     * @return array<ServiceProvider>
     */
    public function providers() : array
    {
        return (new Finder($this->path()))->findProviders();
    }
}
