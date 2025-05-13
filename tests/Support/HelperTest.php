<?php

namespace Octopy\Tests\Support;

use Illuminate\Contracts\Container\BindingResolutionException;
use Octopy\L3D\L3D;
use Octopy\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class HelperTest extends TestCase
{
    /**
     * @throws BindingResolutionException
     */
    #[Test]
    public function l3d() : void
    {
        $this->assertInstanceOf(L3D::class, l3d());
    }
}
