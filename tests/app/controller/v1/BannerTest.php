<?php

namespace app\controller\v1;

use think\testing\TestCase;

class BannerTest extends TestCase
{
    public function testGetBanner()
    {
        $this->visit('/api/v1/banner/getBanner/1')->assertResponseOk();
    }
}
