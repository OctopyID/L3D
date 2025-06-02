<?php

namespace Octopy\Tests\Feature\Filesystem;

use Illuminate\Support\ServiceProvider;
use Octopy\L3D\Domain;
use Octopy\L3D\Filesystem\Finder;
use Octopy\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class FinderTest extends TestCase
{
    /**
     * @var string
     */
    private string $testPath;

    /**
     * @var Finder
     */
    private Finder $finder;

    /**
     * @return void
     */
    protected function setUp() : void
    {
        parent::setUp();

        $this->testPath = app_path('Domain');
        $this->finder = new Finder('Workbench\\App\\Domain\\', $this->testPath);

        if (! is_dir($this->testPath)) {
            mkdir($this->testPath, 0777, true);
        }
    }

    /**
     * @return void
     */
    protected function tearDown() : void
    {
        $this->removeDirectory($this->testPath);
        parent::tearDown();
    }

    /**
     * @return void
     */
    #[Test]
    public function canFindDomainsInDirectory() : void
    {
        foreach (['User', 'Order',] as $domain) {
            mkdir($this->testPath . '/' . $domain);
        }

        $domains = $this->finder->domains();

        $this->assertCount(2, $domains);
        $this->assertArrayHasKey('Workbench\\App\\Domain\\User', $domains);
        $this->assertArrayHasKey('Workbench\\App\\Domain\\Order', $domains);
        $this->assertInstanceOf(Domain::class, $domains['Workbench\\App\\Domain\\User']);
        $this->assertInstanceOf(Domain::class, $domains['Workbench\\App\\Domain\\Order']);
    }

    /**
     * @return void
     */
    #[Test]
    public function canFindServiceProvidersInDomain() : void
    {
        // Setup test domain with provider
        $domainPath = $this->testPath . '/User';
        $providerPath = $domainPath . '/Providers';
        mkdir($domainPath . '/Providers', 0777, true);

        file_put_contents(
            $providerPath . '/UserServiceProvider.php',
            $this->getServiceProviderStub('Workbench\\App\\Domain\\User\\Providers'),
        );

        $domains = $this->finder->domains();

        $this->assertArrayHasKey('Workbench\\App\\Domain\\User', $domains);
        $this->assertCount(1, $domains['Workbench\\App\\Domain\\User']->providers);
        $this->assertEquals('Workbench\\App\\Domain\\User\\Providers\\UserServiceProvider', $domains['Workbench\\App\\Domain\\User']->providers[0]);
    }

    /**
     * @return void
     */
    #[Test]
    public function returnsEmptyArrayWhenNoDomainsFound() : void
    {
        $domains = $this->finder->domains();

        $this->assertIsArray($domains);
        $this->assertEmpty($domains);
    }

    /**
     * @return void
     */
    #[Test]
    public function returnsEmptyProvidersArrayWhenNoProvidersDirectory() : void
    {
        mkdir($this->testPath . '/User');

        $domains = $this->finder->domains();

        $this->assertArrayHasKey('Workbench\\App\\Domain\\User', $domains);
        $this->assertEmpty($domains['Workbench\\App\\Domain\\User']->providers);
    }

    /**
     * @param  string $dir
     * @return void
     */
    private function removeDirectory(string $dir) : void
    {
        if (is_dir($dir)) {
            $files = scandir($dir);
            foreach ($files as $file) {
                if ($file != '.' && $file != '..') {
                    if (is_dir($dir . '/' . $file)) {
                        $this->removeDirectory($dir . '/' . $file);
                    } else {
                        unlink($dir . '/' . $file);
                    }
                }
            }
            rmdir($dir);
        }
    }

    /**
     * @param  string $namespace
     * @return string
     */
    private function getServiceProviderStub(string $namespace) : string
    {
        return <<<PHP
        <?php

        namespace {$namespace};

        use Illuminate\Support\ServiceProvider;

        class UserServiceProvider extends ServiceProvider
        {
            public function register()
            {
            }
        }
        PHP;
    }
}
