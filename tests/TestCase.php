<?php

namespace Octopy\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Octopy\L3D\Providers\DomainServiceProvider;
use function Orchestra\Testbench\package_path;

class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * @return string
     */
    public static function applicationBasePath() : string
    {
        return package_path('laravel');
    }

    /**
     *
     * /**
     * @param  Application $app
     * @return string[]
     */
    public function getPackageProviders($app) : array
    {
        return [
            DomainServiceProvider::class,
        ];
    }
}
