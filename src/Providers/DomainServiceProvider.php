<?php

namespace Octopy\L3D\Providers;

use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider;
use Octopy\L3D\Console\Commands\ModelMakeCommand;
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

        if ($this->app->runningInConsole()) {
            $this->registerPublishing();
        }

        $this
            ->bindToContainer()
            ->registerDomains()
            ->registerCommand();
    }

    /**
     * @return void
     */
    private function registerCommand() : void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            \Octopy\L3D\Console\Commands\ModelMakeCommand::class,
            \Octopy\L3D\Console\Commands\DomainMakeCommand::class,
            \Octopy\L3D\Console\Commands\ControllerMakeCommand::class,
        ]);
    }

    /**
     * @return $this
     * @throws BindingResolutionException
     */
    private function registerDomains() : static
    {
        $domain = $this->app->make('domain');

        $this->loadMigrationsFrom($domain->getMigrationPaths());

        foreach ($domain->getServiceProviders() as $provider) {
            $this->app->register($provider);
        }

        return $this;
    }

    /**
     * @return $this
     */
    private function bindToContainer() : static
    {
        $this->app->alias(Domain::class, 'domain');
        $this->app->singleton(Domain::class, function () {
            return new Domain(config(
                'domain.path'
            ));
        });

        return $this;
    }

    /**
     * @return void
     */
    protected function registerPublishing() : void
    {
        $this->publishes([__DIR__ . '/../../config/domain.php' => config_path('domain.php')], 'domain');
    }
}
