<?php

namespace WebArch\Monitor\Metric\Interfaces;

use DateInterval;
use DateTimeZone;

interface MetricInterface
{
    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name);

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param DateInterval $interval
     * @param null|DateTimeZone $timeZone
     *
     * @return mixed
     */
    public function calculate(DateInterval $interval, DateTimeZone $timeZone = null);
}
