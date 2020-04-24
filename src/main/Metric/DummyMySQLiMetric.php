<?php

namespace WebArch\Monitor\Metric;

use DateInterval;

class DummyMySQLiMetric extends MySQLiAwareMetricBase
{
    /**
     * @inheritDoc
     */
    public function calculate(DateInterval $interval)
    {
        return $this->calculateSimpleSqlMetric('select now() as NOW', 'NOW');
    }

}
