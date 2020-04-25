<?php

namespace WebArch\Monitor\Exception;

use RuntimeException;
use Throwable;
use WebArch\Monitor\Enum\ErrorCode;

class TokenIsNotConfiguredException extends RuntimeException
{
    public function __construct(Throwable $previous = null)
    {
        parent::__construct(
            'Token is not configured.',
            ErrorCode::TOKEN_IS_NOT_CONFIGURED,
            $previous
        );
    }

}
