<?php

namespace Octopy\L3D\Finder;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Octopy\L3D\Util;
use Symfony\Component\Finder\Finder as SymfonyFinder;

class Finder
{
    protected Collection $domains;

    /**
     * @param  string $path
     */
    public function __construct(protected string $path)
    {
        $this->domains = collect([]);
    }

    /**
     * @return Collection
     * @throws Exception
     */
    public function getDomains() : Collection
    {
        if (! is_dir($this->path)) {
            return $this->domains;
        }

        if ($this->domains->isNotEmpty()) {
            return $this->domains;
        }

        $iterable = collect(SymfonyFinder::create()->in($this->path)->directories()->depth(0))->map(function ($row) {
            return $row->getBasename();
        });

        $domains = [];
        foreach ($iterable as $path => $domain) {
            $domains[$domain] = [
                'providers' => [],
                'migration' => null,
            ];

            if (is_dir($path . '/Providers')) {
                $finder = SymfonyFinder::create()->in($path . '/Providers')->files()->name('*.php');
                foreach ($finder as $item) {
                    $provider = Util::getClass($item);

                    if (! is_subclass_of($provider, ServiceProvider::class)) {
                        throw new Exception('Class ' . $provider . ' is not a subclass of ' . ServiceProvider::class);
                    }

                    $domains[$domain]['providers'][] = $provider;
                }
            }

            if (is_dir($migration = sprintf('%s/Database/Migrations', $path))) {
                $domains[$domain]['migration'] = $migration;
            }
        }

        return $this->domains = collect($domains);
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
        return $this->getDomains()->filter(fn($domain) : bool => ! is_null($domain[$type]));
    }
}