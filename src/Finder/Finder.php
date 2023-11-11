<?php

namespace Octopy\L3D\Finder;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Octopy\L3D\Util;
use Symfony\Component\Finder\Finder as SymfonyFinder;

class Finder
{
    protected string $path;

    /**
     * @param  string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * @return Collection
     * @throws Exception
     */
    public function getDomains() : Collection
    {
        if (! is_dir($this->path)) {
            return collect([]);
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

        return collect($domains);
    }
}