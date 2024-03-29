<?php

namespace Octopy\Tests;

use App\Domain\Foo\Providers\FooServiceProvider;
use Exception;
use Octopy\L3D\Cache\Cache;
use Octopy\L3D\Domain;

class DomainTest extends TestCase
{
    protected Domain $domain;

    /**
     * @return void
     */
    protected function setUp() : void
    {
        parent::setUp();

        $this->domain = new Domain(config(
            'domain.path',
        ));
    }

    /**
     * @test
     * @throws Exception
     */
    public function getMigrationPaths() : void
    {
        $this->assertContains(domain_path('Foo/Database/Migrations'), $this->domain->getMigrationPaths());
        $this->assertContains(domain_path('Bar/Database/Migrations'), $this->domain->getMigrationPaths());
    }

    /**
     * @test
     * @throws Exception
     */
    public function getServiceProviders() : void
    {
        $this->assertContains(FooServiceProvider::class, $this->domain->getServiceProviders());
    }
}