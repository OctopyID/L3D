<?php

namespace Octopy\Tests;

use Octopy\L3D\Domain;
use function Octopy\L3D\domain;
use function Octopy\L3D\domain_path;

beforeEach(function () {
    create_fake_domain('A', true);
});

afterEach(function () {
    remove_fake_domain();
});

test('Domain', function () {
    $domain = new Domain('A');

    expect($domain->name())->toBe(('A'))
        ->and($domain->path())->toBe(domain_path('A'))
        ->and($domain->providers())->toEqualCanonicalizing([
            'App\Domain\A\Providers\AServiceProvider',
        ]);
});
