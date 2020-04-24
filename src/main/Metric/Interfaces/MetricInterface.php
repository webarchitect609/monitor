<?php

namespace WebArch\Monitor\Metric\Interfaces;

use DateInterval;

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
     *
     * @return mixed
     */
    public function calculate(DateInterval $interval);
}
