<?php

namespace Octopy\Tests\Console\Commands;

use Illuminate\Support\Facades\File;
use Octopy\Tests\TestCase;

class ModelMakeCommandTest extends TestCase
{
    /**
     * @return void
     */
    protected function tearDown() : void
    {
        File::delete([
            domain_path('Foo/Models/Foo.php'),
        ]);

        parent::tearDown();
    }

    /**
     * @return void
     */
    public function testItCanCreateModel()
    {
        $this
            ->artisan('make:model', [
                'name'     => 'Foo',
                '--domain' => 'Foo',
            ])
            ->assertExitCode(0);

        $this->assertFileExists(domain_path(
            'Foo/Models/Foo.php'
        ));
    }
}