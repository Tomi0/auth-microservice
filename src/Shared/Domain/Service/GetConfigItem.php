<?php

namespace Shared\Domain\Service;

abstract class GetConfigItem
{
    public abstract function execute(string $configKey): mixed;
}
