<?php

namespace WebArch\Monitor\Metric\Interfaces;

use mysqli;

interface MySQLiAwareMetricInterface
{
    /**
     * @param mysqli $mysqli
     *
     * @return $this
     */
    public function setMySQLi(mysqli $mysqli);
}
