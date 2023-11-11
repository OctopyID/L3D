<?php

namespace Octopy\Tests\Console\Commands;

use Octopy\L3D\Support\Facades\Domain;
use Octopy\Tests\TestCase;

class DomainMakeCommandTest extends TestCase
{
    /**
     * @test
     */
    public function ItCanCreateDomainDirectory() : void
    {
        $this->artisan('make:domain', ['name' => 'Baz'])->assertExitCode(0);

        $this->assertDirectoryExists(domain_path(
            'Baz'
        ));
    }
}
