<?php

namespace Octopy\L3D\Providers;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Octopy\L3D\Domain;

class DomainServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot() : void
    {
        //
    }

    /**
     * @return void
     */
    public function register() : void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/domain.php', 'domain'
        );
    }
}
