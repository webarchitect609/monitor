<?php

namespace WebArch\Monitor\Metric;

use WebArch\Monitor\Metric\Interfaces\GuzzleClientAwareMetricInterface;
use WebArch\Monitor\Metric\Traits\GuzzleClientAwareMetricTrait;

abstract class GuzzleClientAwareMetricBase extends MetricBase implements GuzzleClientAwareMetricInterface
{
    use GuzzleClientAwareMetricTrait;
}
