<?php

namespace Octopy\Tests\Feature;

use Octopy\L3D\DomainInfo;
use function Octopy\L3D\domain;
use function Octopy\L3D\domain_path;

beforeEach(function () {
    create_fake_domain('A', true);
});

afterEach(function () {
    remove_fake_domain();
});

it('returns name and path correctly', function () {
    $domain = new DomainInfo('A');

    expect($domain->name())->toBe(('A'))
        ->and($domain->path())->toBe(domain_path('A'));
});

it('can return a list of valid service providers', function () {
    $domain = new DomainInfo('A');

    expect($domain->providers())->toEqualCanonicalizing([
        'App\Domain\A\Providers\AServiceProvider',
    ]);
});
