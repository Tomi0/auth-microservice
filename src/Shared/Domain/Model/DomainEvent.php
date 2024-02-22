<?php

namespace Shared\Domain\Model;

use DateTime;
use JsonSerializable;

interface DomainEvent extends JsonSerializable
{
    public function occurredOn(): DateTime;
}
