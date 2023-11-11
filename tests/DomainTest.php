<?php

namespace Octopy\Tests;

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
        $this->assertContains(app_path('Domain/Foo/Database/Migrations'), $this->domain->getMigrationPaths());
        $this->assertContains(app_path('Domain/Bar/Database/Migrations'), $this->domain->getMigrationPaths());
    }
}