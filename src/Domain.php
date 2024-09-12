<?php

namespace Octopy\L3D;

use SplFileInfo;

class Domain
{
    /**
     * @param  string $domain
     */
    public function __construct(protected string $domain)
    {
        //
    }

    /**
     * @return string
     */
    public function name() : string
    {
        return $this->domain;
    }

    /**
     * @return string
     */
    public function path() : string
    {
        return domain_path($this->domain);
    }
}
