<?php

namespace Octopy\L3D;

use Filament\Panel;
use Octopy\L3D\Finder\Finder;
use Octopy\L3D\Support\Util;

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

    /**
     * @param  Panel $panel
     * @return Panel
     */
    public function filament(Panel $panel) : Panel
    {
        collect($this->domains())->each(function (DomainInfo $domain) use (&$panel) {
            $panel->discoverPages($domain->path('Filament/Pages'), Util::getClass($domain->path('Filament/Pages')));
            $panel->discoverWidgets($domain->path('Filament/Widgets'), Util::getClass($domain->path('Filament/Widgets')));
            $panel->discoverClusters($domain->path('Filament/Clusters'), Util::getClass($domain->path('Filament/Clusters')));
            $panel->discoverResources($domain->path('Filament/Resources'), Util::getClass($domain->path('Filament/Resources')));
        });

        return $panel;
    }
}
