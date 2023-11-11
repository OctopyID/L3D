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

        App::setBasePath(realpath(
            __DIR__ . '/Skeleton'
        ));

        config([
            'domain.path' => App::path('Domain'),
        ]);
    }

    /**
     * @return void
     */
    protected function tearDown() : void
    {
        $excludes = [
            'Foo', 'Bar', 'Baz',
        ];

        foreach (scandir(domain_path(null)) as $domain) {
            if ($domain === '.' || $domain === '..') {
                continue;
            }

            if (! in_array($domain, $excludes)) {
                if (is_dir(App::path('Domain/' . $domain))) {
                    File::deleteDirectory(App::path('Domain/' . $domain));
                }
            }
        }

        parent::tearDown();
    }

    /**
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