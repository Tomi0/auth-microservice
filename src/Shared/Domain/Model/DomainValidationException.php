<?php

namespace Shared\Domain\Model;

use Exception;
use Throwable;

class DomainValidationException extends Exception
{
    private ?string $fieldName;

    public function __construct(?string $fieldName, string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->fieldName = $fieldName;
    }

    public function fieldName(): ?string
    {
        return $this->fieldName;
    }
}
