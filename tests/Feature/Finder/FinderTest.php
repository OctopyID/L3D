<?php

namespace Octopy\Tests\Finder;

use Octopy\L3D\DomainInfo;
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
        new DomainInfo('A'),
        new DomainInfo('B'),
        new DomainInfo('C'),
    ]);

});
