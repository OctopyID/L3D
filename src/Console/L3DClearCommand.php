<?php

namespace Octopy\L3D\Console;

use Illuminate\Console\Command;
use Illuminate\Contracts\Container\BindingResolutionException;

class L3DClearCommand extends Command
{
    /**
     * @var string
     */
    protected $name = 'l3d:clear';

    /**
     * @var string
     */
    protected $description = 'Remove the domain cache file';

    /**
     * @throws BindingResolutionException
     */
    public function handle() : void
    {
        l3d()->cache()->clear();

        $this->components->info('Domain cache cleared successfully.');
    }
}
