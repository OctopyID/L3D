<?php

namespace Octopy\Tests\Feature;

use Octopy\L3D\Domain;
use Octopy\L3D\DomainInfo;
use function Octopy\L3D\domain;
use function Octopy\L3D\domain_path;

beforeEach(function () {
    create_fake_domain([
        'A', 'B', 'C',
    ], true);
});

afterEach(function () {
    remove_fake_domain();
});

it('can return a list of valid service providers in all domains', function () {
    $domain = new Domain;

    expect($domain->providers())->toEqualCanonicalizing([
        'App\Domain\A\Providers\AServiceProvider',
        'App\Domain\B\Providers\BServiceProvider',
        'App\Domain\C\Providers\CServiceProvider',
    ]);
});
