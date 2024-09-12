<?php

namespace Octopy\Tests\Finder;

use Octopy\L3D\Domain;
use Octopy\L3D\Finder\Finder;

beforeEach(function () {
    create_fake_domain([
        'A', 'B', 'C',
    ]);
});

afterEach(function () {
    remove_fake_domain();
});

it('should return array of domains', function () {
    $finder = new Finder(config(
        'domain.path'
    ));

    expect($finder->findDomains())->toEqualCanonicalizing([
        new Domain('A'),
        new Domain('B'),
        new Domain('C'),
    ]);

    remove_fake_domain();
});
