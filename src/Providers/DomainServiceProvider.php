<?php

namespace Octopy\L3D\Providers;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Octopy\L3D\Console\Commands\DomainMakeCommand;
use Octopy\L3D\Domain;
use Illuminate\Database\Eloquent\Factories\Factory;

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
     * @throws BindingResolutionException
     */
    public function register() : void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/domain.php', 'domain'
        );

        if ($this->app->runningInConsole()) {
            $this->registerPublishing();
            $this->guessDomainFactory();
        }

        $this
            ->registerDomains()
            ->registerCommand();
    }

    /**
     * @return void
     */
    private function registerCommand() : void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                DomainMakeCommand::class,
            ]);
        }
    }

    /**
     * @throws BindingResolutionException
     */
    private function registerDomains() : self
    {
        /**
         * @var $domain Domain
         */
        $domain = $this->app->make(Domain::class);

        $this->loadMigrationsFrom($domain->migrations());

        foreach ($domain->providers() as $provider) {
            $this->app->register($provider);
        }

        return $this;
    }

    /**
     * @return void
     */
    private function registerPublishing() : void
    {
        $this
            ->publishes([
                __DIR__ . '/../../config/domain.php' => config_path('domain.php'),
            ], 'domain');
    }

    /**
     * @return void
     */
    private function guessDomainFactory() : void
    {
        Factory::guessFactoryNamesUsing(function ($name) {
            return str_replace('Models', 'Database\\Factories', $name) . 'Factory';
        });
    }
}
