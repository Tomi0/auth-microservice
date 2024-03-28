<?php

namespace Authentication\Domain\Model\User;

use Shared\Domain\Model\DomainValidationException;

class EmailAlreadyInUseException extends DomainValidationException
{

}
