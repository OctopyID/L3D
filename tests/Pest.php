<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

use Octopy\Tests\TestCase;
use Symfony\Component\Finder\Finder as SymfonyFinder;
use function Octopy\L3D\domain_path;
use function Orchestra\Testbench\package_path;

pest()->extend(TestCase::class);

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toEqualDomainOf', function (string $domain) {
    return $this->toEqual(new \Octopy\L3D\DomainInfo($domain));
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function create_fake_domain(string|array $domain, bool $provider = false) : void
{
    if (is_array($domain)) {
        foreach ($domain as $value) {
            create_fake_domain($value, $provider);
        }

        return;
    }

    if (! is_dir(domain_path($domain))) {
        mkdir(domain_path($domain), recursive: true);
    }

    if ($provider) {
        if (! is_dir(domain_path(sprintf('%s/Providers', $domain)))) {
            mkdir(domain_path(sprintf('%s/Providers', $domain)), recursive: true);
        }

        $stub = file_get_contents(package_path('laravel/stubs/ServiceProvider.stub'));

        file_put_contents(
            domain_path(sprintf('%s/Providers/%sServiceProvider.php', $domain, $domain)), str($stub)->replace('{{DOMAIN}}', $domain)
        );
    }
}

function remove_fake_domain() : void
{
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator(domain_path('/'), FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST
    );

    foreach ($files as $file) {
        is_dir($file->getRealPath()) ? rmdir($file) : unlink($file);
    }
}
