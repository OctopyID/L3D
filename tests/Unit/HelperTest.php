<?php

namespace Octopy\Tests\Unit;

use Illuminate\Contracts\Container\BindingResolutionException;
use Octopy\L3D\L3D;
use Octopy\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class HelperTest extends TestCase
{
    /**
     * @return void
     * @throws BindingResolutionException
     */
    #[Test]
    public function l3dFunctionReturnL3DInstance() : void
    {
        $mockL3D = $this->mock(L3D::class);

        $this->app->instance(L3D::class, $mockL3D);

        $result = l3d();

        $this->assertSame($mockL3D, $result);
    }
}
