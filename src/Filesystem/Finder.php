<?php

namespace Octopy\L3D\Filesystem;

use Illuminate\Support\ServiceProvider;
use Octopy\L3D\Contracts\L3DExcludable;
use Octopy\L3D\Domain;
use Symfony\Component\Finder\Finder as SymfonyFinder;
use Symfony\Component\Finder\SplFileInfo;

class Finder
{
    /**
     * @var SymfonyFinder
     */
    protected SymfonyFinder $finder;

    /**
     * @param  string $namespace
     * @param  string $location
     */
    public function __construct(protected string $namespace, protected string $location)
    {
        $this->finder = new SymfonyFinder;
    }

    /**
     * @return Domain[]
     */
    public function domains() : array
    {
        $domains = [];

        $results = $this->finder->in($this->location)->directories()->depth(0);

        foreach ($results as $spl) {
            $domain = tap(new Domain($this->getNamespace($spl), $spl->getRealPath()), function (Domain $domain) {
                $domain->providers = $this->findServiceProviders($domain);
            });

            $domains[$domain->namespace] = $domain;
        }

        return $domains;
    }

    /**
     * @param  Domain $domain
     * @return array
     */
    private function findServiceProviders(Domain $domain) : array
    {
        $providers = [];
        if (! is_dir($domain->basepath('Providers'))) {
            return [];
        }

        $files = $this->finder->in($domain->basepath('Providers'))->files();

        $files = new SymfonyFinder()->in($domain->basepath('Providers'))->files();

        foreach ($files as $file) {
            $relatives = $file->getRelativePath();
            $namespace = $relatives ? str_replace('/', '\\', $relatives) . '\\' : '';

            $provider = $domain->namespace . '\\Providers\\' . $namespace . $file->getBasename('.php');

            if (is_subclass_of($provider, ServiceProvider::class) && ! is_subclass_of($provider, L3DExcludable::class)) {
                $providers[] = $provider;
            }
        }

        return $providers;
    }

    /**
     * @param  SplFileInfo $spl
     * @return string
     */
    private function getNamespace(SplFileInfo $spl) : string
    {
        return $this->namespace . $spl->getBasename();
    }
}
