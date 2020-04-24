<?php

namespace WebArch\Monitor\Service\Interfaces;

use DateInterval;
use WebArch\Monitor\Exception\MetricNotFoundException;
use WebArch\Monitor\Metric\Interfaces\MetricInterface;

interface MonitorInterface
{

    /**
     * @param \WebArch\Monitor\Metric\Interfaces\MetricInterface $metric
     *
     * @return $this
     */
    public function addMetric(MetricInterface $metric);

    /**
     * @param DateInterval $interval
     *
     * @return $this
     */
    public function setInterval(DateInterval $interval);

    /**
     * @return DateInterval
     */
    public function getInterval(): DateInterval;

    /**
     * @param string $metricName
     *
     * @throws MetricNotFoundException
     * @return mixed
     */
    public function evalMetricByName(string $metricName);

    /**
     * @param string $metricName
     *
     * @return string
     */
    public function exec(string $metricName): string;
}
