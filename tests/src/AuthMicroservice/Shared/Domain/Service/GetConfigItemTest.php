<?php

namespace Tests\src\AuthMicroservice\Shared\Domain\Service;

use Illuminate\Support\Facades\Config;
use Shared\Domain\Service\GetConfigItem;
use Tests\TestCase;

class GetConfigItemTest extends TestCase
{
    private GetConfigItem $getConfigItem;

    protected function setUp(): void
    {
        parent::setUp();
        $this->getConfigItem = $this->app->make(GetConfigItem::class);
    }


    public function testReturnCorrectBoolValue(): void
    {
        $key = 'auth.enable_authorized_host';
        $value = true;
        Config::set($key, $value);

        $ret = $this->getConfigItem->execute($key);

        $this->assertEquals($ret, $value);
    }


    public function testReturnCorrectStringValue(): void
    {
        $key = 'auth.enable_authorized_host';
        $value = 'manolo';
        Config::set($key, $value);

        $ret = $this->getConfigItem->execute($key);

        $this->assertEquals($ret, $value);
    }


    public function testReturnCorrectIntValue(): void
    {
        $key = 'auth.enable_authorized_host';
        $value = 'manolo';
        Config::set($key, $value);

        $ret = $this->getConfigItem->execute($key);

        $this->assertEquals($ret, $value);
    }
}
