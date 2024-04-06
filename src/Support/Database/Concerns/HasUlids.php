<?php

namespace Octopy\L3D\Support\Database\Concerns;

use Illuminate\Support\Str;

trait HasUlids
{
    use \Illuminate\Database\Eloquent\Concerns\HasUlids;

    /**
     * @return string
     */
    public function newUniqueId() : string
    {
        return strtoupper((string) Str::ulid());
    }
}
