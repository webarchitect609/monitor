<?php

namespace WebArch\Monitor\Exception;

use Throwable;
use WebArch\Monitor\Enum\ErrorCode;

class InvalidArgumentException extends \InvalidArgumentException
{
    public function __construct($message = "", $code = ErrorCode::OTHER_ERROR, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
