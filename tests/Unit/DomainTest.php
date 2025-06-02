<?php

namespace Octopy\Tests\Unit;

use Octopy\L3D\Domain;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class DomainTest extends TestCase
{
    /**
     * @return void
     */
    #[Test]
    public function canCreateDomainInstance()
    {
        $domain = new Domain('App\Test', '/path/to/test');

        $this->assertEquals('App\Test', $domain->namespace);
        $this->assertEquals('/path/to/test', $domain->realpath);
        $this->assertEquals('/path/to/test/Database/Migrations', $domain->migration);
    }

    /**
     * @return void
     */
    #[Test]
    public function canGenerateCorrectPath()
    {
        $domain = new Domain('App\Test', '/base/path');

        $result = $domain->basepath('controllers');

        $this->assertEquals('/base/path/controllers', $result);
    }

    /**
     * @return void
     */
    #[Test]
    public function canConvertToArray()
    {
        $domain = new Domain('App\Test', '/test/path');
        $domain->providers = [
            'TestProvider',
        ];

        $result = $domain->toArray();

        $expectedArray = [
            'realpath'  => '/test/path',
            'namespace' => 'App\Test',
            'providers' => ['TestProvider'],
            'migration' => '/test/path/Database/Migrations',
        ];

        $this->assertEquals($expectedArray, $result);
        $this->assertIsArray($result);

        foreach ($expectedArray as $key => $value) {
            $this->assertArrayHasKey($key, $result);
        }
    }

    /**
     * @return void
     */
    #[Test]
    public function hasEmptyProvidersByDefault()
    {
        $domain = new Domain('App\Test', '/test');

        $this->assertIsArray($domain->providers);
        $this->assertEmpty($domain->providers);
    }
}
