<?php

namespace WebArch\Monitor\Metric;

use DateInterval;
use DateTimeImmutable;
use DateTimeZone;
use Exception;
use WebArch\Monitor\Metric\Interfaces\MetricInterface;

abstract class MetricBase implements MetricInterface
{
    /**
     * @var string
     */
    private $name = '';

    public function __construct(string $name)
    {
        $this->setName($name);
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param DateInterval $interval
     * @param DateTimeZone $timeZone
     *
     * @throws Exception
     * @return DateTimeImmutable
     */
    protected function getIntervalStartDateTime(
        DateInterval $interval,
        DateTimeZone $timeZone = null
    ): DateTimeImmutable {
        return (new DateTimeImmutable('now', $timeZone))->sub($interval);
    }

}
