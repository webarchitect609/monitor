<?php

namespace WebArch\Monitor\Service;

use DateInterval;
use DateTimeZone;
use Throwable;
use WebArch\Monitor\Exception\InvalidArgumentException;
use WebArch\Monitor\Exception\InvalidTokenException;
use WebArch\Monitor\Exception\MetricIsNotFoundException;
use WebArch\Monitor\Exception\TokenIsNotConfiguredException;
use WebArch\Monitor\Metric\Interfaces\MetricInterface;
use WebArch\Monitor\Service\Interfaces\MonitorInterface;

abstract class MonitorBase implements MonitorInterface
{
    /**
     * X-Monitor-Token
     */
    public const TOKEN_HEADER_KEY = 'HTTP_X_MONITOR_TOKEN';

    /**
     * @var MetricInterface[]
     */
    protected $metrics = [];

    /**
     * @var DateInterval
     */
    protected $interval;

    /**
     * @var null|DateTimeZone
     */
    protected $timeZone;

    public function __construct(string $token)
    {
        $this->checkToken($token);
    }

    /**
     * Performs additional configuration of the $metric. For example, calls setMysqli() and enables $metric to make
     * SQL-queries.
     *
     * @param MetricInterface $metric
     *
     * @return MetricInterface
     */
    abstract protected function doConfigureMetric(MetricInterface $metric): MetricInterface;

    /**
     * @param string $token
     *
     * @throws TokenIsNotConfiguredException
     * @throws InvalidTokenException
     */
    protected function checkToken(string $token)
    {
        $token = trim($token);
        if ('' === $token) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            throw new TokenIsNotConfiguredException();
        }

        if ($_SERVER[self::TOKEN_HEADER_KEY] !== $token) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 401 Unauthorized', true, 401);
            throw new InvalidTokenException();
        }
    }

    /**
     * @inheritDoc
     */
    public function getInterval(): DateInterval
    {
        return $this->interval;
    }

    /**
     * @inheritDoc
     */
    public function setInterval(DateInterval $interval)
    {
        $this->interval = $interval;

        return $this;
    }

    /**
     * @return null|DateTimeZone
     */
    public function getTimeZone(): ?DateTimeZone
    {
        return $this->timeZone;
    }

    /**
     * @param null|DateTimeZone $timeZone
     *
     * @return $this
     */
    public function setTimeZone(?DateTimeZone $timeZone)
    {
        $this->timeZone = $timeZone;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addMetric(MetricInterface $metric)
    {
        if (array_key_exists($metric->getName(), $this->metrics)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Metric "%s" already added.',
                    $metric->getName()
                )
            );
        }
        $this->metrics[$metric->getName()] = $metric;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function evalMetricByName(string $metricName)
    {
        if (!array_key_exists($metricName, $this->metrics)) {
            throw new MetricIsNotFoundException($metricName);
        }

        return $this->doConfigureMetric($this->metrics[$metricName])
                    ->calculate($this->getInterval(), $this->getTimeZone());
    }

    /**
     * @inheritDoc
     */
    public function exec(string $metricName): string
    {
        /**
         * It's safe to return the exception description here, because the Token has been checked.
         */
        try {
            return $this->evalMetricByName($metricName);
        } catch (MetricIsNotFoundException $exception) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request', true, 400);
            throw $exception;
        } catch (Throwable $exception) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            throw $exception;
        }
    }
}
