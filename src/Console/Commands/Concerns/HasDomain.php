<?php

namespace Octopy\L3D\Console\Commands\Concerns;

use Symfony\Component\Console\Input\InputOption;

trait HasDomain
{
    /**
     * @return array
     */
    protected function getOptions() : array
    {
        return array_merge(parent::getOptions(), [
            ['domain', 'd', InputOption::VALUE_REQUIRED, 'Manually specify the domain to use'],
        ]);
    }
}