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
    return $this->toEqual(new \Octopy\L3D\Domain($domain));
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

function create_fake_domain(string|array $domain) : void
{
    if (is_array($domain)) {
        foreach ($domain as $value) {
            create_fake_domain($value);
        }

        return;
    }

    if (! is_dir(domain_path($domain))) {
        mkdir(domain_path($domain), recursive: true);
    }
}

function remove_fake_domain() : void
{
    collect(SymfonyFinder::create()->in(domain_path('/'))->directories())->each(fn(string $domain) => rmdir($domain));
}
