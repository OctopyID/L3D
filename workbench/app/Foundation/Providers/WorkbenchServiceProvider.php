<?php

namespace Workbench\App\Foundation\Providers;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider;

class WorkbenchServiceProvider extends ServiceProvider
{
    /**
     * @throws BindingResolutionException
     */
    public function register() : void
    {
        l3d()->register([
            'Workbench\\App\\Domain\\' => app_path('Domain'),
        ]);
    }
}
