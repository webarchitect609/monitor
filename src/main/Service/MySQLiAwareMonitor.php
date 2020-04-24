<?php

namespace WebArch\Monitor\Service;

use mysqli;
use WebArch\Monitor\Metric\Interfaces\MetricInterface;
use WebArch\Monitor\Metric\Interfaces\MySQLiAwareMetricInterface;

class MySQLiAwareMonitor extends MonitorBase
{
    /**
     * @var mysqli
     */
    private $mysqli;

    public function __construct(string $token, mysqli $mysqli)
    {
        parent::__construct($token);
        $this->mysqli = $mysqli;
    }

    /**
     * @param string $token
     * @param string $host
     * @param string $username
     * @param string $password
     * @param string $dbname
     * @param int $port
     * @param string $socket
     *
     * @return MySQLiAwareMonitor
     */
    public static function create(
        string $token,
        string $host = null,
        string $username = null,
        string $password = null,
        string $dbname = null,
        int $port = null,
        string $socket = null
    ): MySQLiAwareMonitor {
        return new static(
            $token,
            new mysqli($host, $username, $password, $dbname, $port, $socket)
        );
    }

    /**
     * @inheritDoc
     */
    protected function doConfigureMetric(MetricInterface $metric): MetricInterface
    {
        if ($metric instanceof MySQLiAwareMetricInterface) {
            $metric->setMySQLi($this->mysqli);
        }

        return $metric;
    }
}
