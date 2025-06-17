<?php

namespace Octopy\L3D\Console;

use Illuminate\Console\Command;
use Illuminate\Contracts\Container\BindingResolutionException;
use Octopy\L3D\Exceptions\L3DCacheException;
use Octopy\L3D\L3D;

class L3DCacheCommand extends Command
{
    /**
     * @var string
     */
    protected $name = 'l3d:cache';

    /**
     * @var string
     */
    protected $description = 'Create a cache file for faster domain configuration loading';

    /**
     * @throws L3DCacheException
     * @throws BindingResolutionException
     */
    public function handle() : void
    {
        l3d()->cache->clear();

        l3d()->bootstrap(function (L3D $l3d) {
            $l3d->cache->put($l3d->domains());
        });

        $this->components->info('Domain cached successfully.');
    }
}
