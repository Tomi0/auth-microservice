<?php

namespace Shared\Domain\Model;

use DateTime;

interface DomainEvent
{
    public function occurredOn(): DateTime;
}
