<?php

namespace WebArch\Monitor\Service\Interfaces;

use DateInterval;
use DateTimeZone;
use Throwable;
use WebArch\Monitor\Exception\InvalidArgumentException;
use WebArch\Monitor\Exception\MetricIsNotFoundException;
use WebArch\Monitor\Metric\Interfaces\MetricInterface;

interface MonitorInterface
{

    /**
     * @param MetricInterface $metric
     *
     * @throws InvalidArgumentException
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
     * @param null|DateTimeZone $timeZone
     *
     * @return $this
     */
    public function setTimeZone(?DateTimeZone $timeZone);

    /**
     * @return null|DateTimeZone
     */
    public function getTimeZone(): ?DateTimeZone;

    /**
     * @param string $metricName
     *
     * @throws MetricIsNotFoundException
     * @return mixed
     */
    public function evalMetricByName(string $metricName);

    /**
     * @param string $metricName
     *
     * @throws MetricIsNotFoundException
     * @throws Throwable
     * @return string
     */
    public function exec(string $metricName): string;
}
