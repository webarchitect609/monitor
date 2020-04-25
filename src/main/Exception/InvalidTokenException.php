<?php

namespace WebArch\Monitor\Exception;

use RuntimeException;
use Throwable;
use WebArch\Monitor\Enum\ErrorCode;

class InvalidTokenException extends RuntimeException
{
    public function __construct(Throwable $previous = null)
    {
        parent::__construct('Invalid token.', ErrorCode::INVALID_TOKEN, $previous);
    }

}
