<?php

namespace Octopy\Tests\Feature\Console\Commands;

use Illuminate\Console\Command;
use Mockery\MockInterface;
use Octopy\L3D\Console\L3DCacheCommand;
use Octopy\L3D\Console\L3DClearCommand;
use Octopy\L3D\Domain;
use Octopy\L3D\Exceptions\L3DCacheException;
use Octopy\L3D\Filesystem\Cache;
use Octopy\L3D\L3D;
use Octopy\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class L3DCommandTest extends TestCase
{
    /**
     * @var L3D|MockInterface
     */
    private L3D|MockInterface $l3d;

    /**
     * @var L3DCacheCommand
     */
    private L3DCacheCommand $cacheCommand;

    /**
     * @var L3DClearCommand
     */
    private L3DClearCommand $clearCommand;

    /**
     * @return void
     */
    protected function setUp() : void
    {
        parent::setUp();

        $this->cacheCommand = new L3DCacheCommand;
        $this->clearCommand = new L3DClearCommand;
        $this->l3d = $this->mock(L3D::class);
        $this->app->instance(L3D::class, $this->l3d);
    }

    /**
     * @return void
     */
    #[Test]
    public function cacheCommandAttributes() : void
    {
        $this->assertEquals('l3d:cache', $this->cacheCommand->getName());
        $this->assertEquals(
            'Create a cache file for faster domain configuration loading', $this->cacheCommand->getDescription(),
        );
    }

    /**
     * @return void
     */
    #[Test]
    public function clearCommandAttributes() : void
    {
        $this->assertEquals('l3d:clear', $this->clearCommand->getName());
        $this->assertEquals(
            'Remove the domain cache file', $this->clearCommand->getDescription(),
        );
    }

    /**
     * @return void
     */
    #[Test]
    public function cacheCommandCachesDomainsSuccessfully() : void
    {
        $cache = $this->mock(Cache::class);
        $domains = [
            'App\\Domain\\Test' => new Domain('App\\Domain\\Test', '/test/path'),
        ];

        $this->l3d->shouldReceive('cache')->twice()->andReturn($cache);
        $this->l3d->shouldReceive('bootstrap')
            ->once()
            ->withArgs(function ($callback) use ($domains) {
                $this->l3d->shouldReceive('domains')->once()->andReturn($domains);

                $callback(
                    $this->l3d,
                );

                return true;
            });

        $cache->shouldReceive('clear')->once()->andReturnTrue();
        $cache->shouldReceive('put')->once()->with($domains)->andReturnTrue();

        $this->artisan('l3d:cache')->assertSuccessful();
    }

    /**
     * @return void
     */
    #[Test]
    public function clearCommandClearsCache() : void
    {
        $cache = $this->mock(Cache::class);

        $this->l3d->shouldReceive('cache')->once()->andReturn($cache);
        $cache->shouldReceive('clear')->once()->andReturnTrue();

        $this->artisan('l3d:clear')->assertSuccessful();
    }
}
