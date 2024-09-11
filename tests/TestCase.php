<?php

namespace Octopy\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Octopy\L3D\Providers\DomainServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * @return void
     */
    protected function setUp() : void
    {
        parent::setUp();

        App::setBasePath(
            __DIR__ . '/Laravel'
        );
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
