<?php

namespace WebArch\Monitor\Metric;

use DateInterval;
use DateTimeZone;

class DummyMySQLiMetric extends MySQLiAwareMetricBase
{
    /**
     * @inheritDoc
     */
    public function calculate(DateInterval $interval, DateTimeZone $timeZone = null)
    {
        return $this->calculateSimpleSqlMetric('select now() as NOW', 'NOW');
    }

}
