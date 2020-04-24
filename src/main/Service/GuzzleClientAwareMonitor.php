<?php

namespace WebArch\Monitor\Service;

use GuzzleHttp\Client;
use WebArch\Monitor\Metric\Interfaces\GuzzleClientAwareMetricInterface;
use WebArch\Monitor\Metric\Interfaces\MetricInterface;

class GuzzleClientAwareMonitor extends MonitorBase
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(string $token, Client $client)
    {
        parent::__construct($token);
        $this->client = $client;
    }

    /**
     * @inheritDoc
     */
    protected function doConfigureMetric(MetricInterface $metric): MetricInterface
    {
        if ($metric instanceof GuzzleClientAwareMetricInterface) {
            $metric->setClient($this->client);
        }

        return $metric;
    }

}
