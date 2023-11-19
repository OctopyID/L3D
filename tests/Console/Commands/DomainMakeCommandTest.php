<?php

namespace Octopy\Tests\Console\Commands;

use Illuminate\Support\Facades\File;
use Octopy\Tests\TestCase;

class DomainMakeCommandTest extends TestCase
{
    /**
     * @return void
     */
    protected function tearDown() : void
    {
        File::deleteDirectory(domain_path(
            'Baz'
        ));

        parent::tearDown();
    }

    /**
     * @test
     */
    public function ItCanCreateDomainDirectory() : void
    {
        $this->artisan('make:domain', ['name' => 'Baz'])->assertExitCode(0);

        $paths = [
            'Models',
            'Providers',
            'Http/Middleware',
            'Http/Controllers',
        ];

        foreach ($paths as $path) {
            $this->assertDirectoryExists(domain_path(
                'Baz/' . $path
            ));
        }

        foreach (['web.php', 'api.php'] as $file) {
            $this->assertFileExists(domain_path(
                'Baz/' . $file
            ));
        }
    }
}
