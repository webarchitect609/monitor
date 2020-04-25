<?php

namespace WebArch\Monitor\Exception;

use RuntimeException;
use Throwable;
use WebArch\Monitor\Enum\ErrorCode;

class MetricIsNotFoundException extends RuntimeException
{
    public function __construct(string $metricName, Throwable $previous = null)
    {
        parent::__construct(
            sprintf(
                'Metric %s does not exist.',
                $metricName
            ),
            ErrorCode::METRIC_IS_NOT_FOUND,
            $previous
        );
    }

}
