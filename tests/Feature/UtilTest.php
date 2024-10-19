<?php

namespace Octopy\Tests\Feature;

use Octopy\L3D\DomainInfo;
use Octopy\L3D\Support\Util;
use function Octopy\L3D\domain;
use function Octopy\L3D\domain_path;

test('convert path to class name', function () {
    expect('App\\Http\\Controllers\\HomeController')->toBe(Util::getClass(
        app_path('Http/Controllers/HomeController.php')
    ));
});

test('returns an instance of the Domain class', function () {
    expect(domain('A'))->toBeInstanceOf(DomainInfo::class);
});

test('return correct path', function () {
    expect(domain_path('A///B'))->toBe(app()->path('Domain/A/B'));
});
