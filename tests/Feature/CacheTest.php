<?php

namespace Octopy\Tests\Feature;

use Octopy\L3D\Domain;
use Octopy\L3D\Filesystem\Cache;
use Octopy\L3D\L3DCacheException;
use Octopy\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CacheTest extends TestCase
{
    /**
     * @var Cache
     */
    private Cache $cache;

    /**
     * @var string
     */
    private string $cacheDir;

    /**
     * @var string
     */
    private string $cachePath;

    /**
     * @return void
     */
    protected function setUp() : void
    {
        parent::setUp();

        $this->cache = new Cache();
        $this->cacheDir = storage_path('framework/cache');
        $this->cachePath = storage_path('framework/cache/l3d.json');

        // Bersihkan cache sebelum setiap test
        if (file_exists($this->cachePath)) {
            unlink($this->cachePath);
        }
    }

    /**
     * @return void
     */
    protected function tearDown() : void
    {
        // Bersihkan cache setelah setiap test
        if (file_exists($this->cachePath)) {
            unlink($this->cachePath);
        }

        parent::tearDown();
    }

    /**
     * @return void
     */
    #[Test]
    public function cacheFileExistenceCanBeChecked()
    {
        $this->assertFalse($this->cache->exists());

        file_put_contents($this->cachePath, '[]');

        $this->assertTrue($this->cache->exists());
    }

    /**
     * @return void
     * @throws L3DCacheException
     */
    #[Test]
    public function throwsExceptionWhenReadingNonExistentCache()
    {
        $this->expectException(L3DCacheException::class);
        $this->expectExceptionMessage('L3D cache not found.');

        $this->cache->get();
    }

    /**
     * @return void
     * @throws L3DCacheException
     */
    #[Test]
    public function canStoreAndRetrieveDomains()
    {
        $domains = [
            new Domain('App\\Domain1', '/path/to/domain1'),
            new Domain('App\\Domain2', '/path/to/domain2'),
        ];

        $this->cache->put($domains);

        $retrievedDomains = $this->cache->get();

        $this->assertCount(2, $retrievedDomains);
        $this->assertContainsOnlyInstancesOf(Domain::class, $retrievedDomains);

        $firstDomain = $retrievedDomains['App\\Domain1'];
        $this->assertEquals('App\\Domain1', $firstDomain->namespace);
        $this->assertEquals('/path/to/domain1', $firstDomain->realpath);
    }

    /**
     * @return void
     * @throws L3DCacheException
     */
    #[Test]
    public function canCreateCacheDirectoryIfNotExists()
    {
        // Hapus direktori cache jika ada
        if (is_dir($this->cacheDir)) {
            rmdir($this->cacheDir);
        }

        $domains = [
            new Domain('App\\Test', '/path/to/test'),
        ];

        $this->cache->put($domains);

        $this->assertDirectoryExists($this->cacheDir);
        $this->assertFileExists($this->cachePath);
    }

    /**
     * @return void
     * @throws L3DCacheException
     */
    #[Test]
    public function canClearCache()
    {
        // Buat file cache terlebih dahulu
        $domains = [
            new Domain('App\\Test', '/path/to/test'),
        ];
        $this->cache->put($domains);

        $this->assertTrue($this->cache->exists());

        $result = $this->cache->clear();

        $this->assertTrue($result);
        $this->assertFalse($this->cache->exists());
    }

    /**
     * @return void
     */
    #[Test]
    public function clearingNonExistentCacheReturnsTrue()
    {
        $this->assertFalse($this->cache->exists());

        $result = $this->cache->clear();

        $this->assertTrue($result);
    }
}
