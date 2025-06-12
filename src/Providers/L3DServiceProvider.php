<?php

namespace Octopy\L3D\Providers;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Octopy\L3D\Console\L3DCacheCommand;
use Octopy\L3D\Console\L3DClearCommand;
use Octopy\L3D\L3D;

class L3DServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register() : void
    {
        $this->app->singleton(L3D::class, function () {
            return
                new L3D([
                    // 'App\\' => app_path('Domain'),
                ]);
        });

        /**
         * register callback to be executed during the application's boot process
         * using booting() instead of boot() ensures this runs before the application is fully booted
         * this allows us to register additional service providers discovered by L3D before boot completion
         */
        $this->app->booting(function () {
            /**
             * resolve L3D instance and bootstrap it by scanning for domain providers
             * Then execute callback with discovered providers for registration
             */
            $this->app->make(L3D::class)->bootstrap(function (L3D $l3d) {
                /**
                 * register each discovered service provider from L3D domains
                 * This allows domain-specific providers to be loaded automatically
                 */
                foreach ($l3d->providers() as $provider) {
                    $this->app->register($provider);
                }

                $this->loadMigrationsFrom($l3d->migrations(
                    strict: true, // ensure only existing directories are scanned
                ));
            });
        });

        $this->guessDomainFactory();

        if ($this->app->runningInConsole()) {
            $this->commands([
                L3DCacheCommand::class,
                L3DClearCommand::class,
            ]);
        }
    }

    /**
     * Configures the factory resolver to locate factory classes within the domain structure.
     * Replaces 'Models' namespace segment with 'Database/Factories' and appends 'Factory' suffix.
     *
     * Example:
     * App\Domains\User\Models\User -> App\Domains\User\Database\Factories\UserFactory
     *
     * This enables model factories to be organized within their respective domains
     * rather than in a central factories' directory.
     *
     * @return void
     */
    private function guessDomainFactory() : void
    {
        Gate::guessPolicyNamesUsing(function (string $name) {
            return str_replace('Models', 'Policies', $name) . 'Policy';
        });

        Factory::guessFactoryNamesUsing(function (string $name) {
            return str_replace('Models', 'Database\\Factories', $name) . 'Factory';
        });
    }
}
