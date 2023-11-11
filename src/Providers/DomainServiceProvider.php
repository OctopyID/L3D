<?php

namespace Octopy\L3D\Providers;

use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Octopy\L3D\Domain;

class DomainServiceProvider extends ServiceProvider
{
    /**
     * @return void
     * @throws Exception
     */
    public function register() : void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/domain.php', 'domain'
        );

        $this->app->singleton(Domain::class, function () {
            return new Domain(App::path(
                'Domain'
            ));
        });

        $domain = $this->app->make(Domain::class);

        $this->loadMigrationsFrom($domain->getMigrationPaths());

        foreach ($domain->getServiceProviders() as $provider) {
            $this->app->register($provider);
        }

        if ($this->app->runningInConsole()) {
            $this->commands([
                \Octopy\L3D\Console\Commands\DomainMakeCommand::class,
                \Octopy\L3D\Console\Commands\ControllerMakeCommand::class,
            ]);
        }
    }
}