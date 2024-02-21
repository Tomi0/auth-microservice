<?php

namespace Shared\Infrastructure\Laravel\Domain\Service;

use Illuminate\Support\Facades\Config;
use Shared\Domain\Service\GetConfigItem;

class GetConfigItemLaravel extends GetConfigItem
{

    public function execute(string $configKey): mixed
    {
        return Config::get($configKey);
    }
}
