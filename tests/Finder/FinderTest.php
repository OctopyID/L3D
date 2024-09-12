<?php

namespace Octopy\Tests\Finder;

use Octopy\L3D\Domain;
use Octopy\L3D\Finder\Finder;

it('should return array of domains', function () {
    $finder = new Finder(config(
        'domain.path'
    ));

    expect($finder->findDomains())->toEqualCanonicalizing([
        new Domain('A'),
        new Domain('B'),
        new Domain('C'),
    ]);
});
