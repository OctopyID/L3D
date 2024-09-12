<?php

namespace Octopy\L3D\Finder;

use Octopy\L3D\Domain;
use Symfony\Component\Finder\Finder as SymfonyFinder;

class Finder
{
    protected SymfonyFinder $finder;

    /**
     * Domain constructor.
     */
    public function __construct(protected string $location)
    {
        $this->finder = new SymfonyFinder;
    }

    /**
     * @return Domain[]
     */
    public function findDomains() : array
    {
        if (! is_dir($this->location)) {
            return [];
        }

        $domains = [];
        foreach ($this->finder->in($this->location)->directories()->depth(0) as $domain) {
            $domains[] = new Domain($domain->getFilename());
        }

        return $domains;
    }
}
