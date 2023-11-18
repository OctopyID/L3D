<?php

namespace Octopy\Tests\Console\Commands;

use Illuminate\Support\Facades\File;
use Octopy\Tests\TestCase;

class ControllerMakeCommandTest extends TestCase
{
    /**
     * @return void
     */
    protected function tearDown() : void
    {
        File::delete([
//            domain_path('Foo/Http/Controllers/FooController.php'),
        ]);

        parent::tearDown();
    }

    /**
     * @test
     */
    public function ItCanCreateController() : void
    {
        $this
            ->artisan('make:controller', [
                'name'     => 'FooController',
                '--domain' => 'Foo',
            ])
            ->assertExitCode(0);

        $this->assertFileExists(domain_path(
            'Foo/Http/Controllers/FooController.php'
        ));
    }
}