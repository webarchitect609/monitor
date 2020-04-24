<?php

namespace WebArch\Monitor\Metric\Traits;

use mysqli;

trait MySQLiAwareMetricTrait
{
    /**
     * @var mysqli
     */
    private $mysqli;

    /**
     * @return mysqli
     */
    protected function getMysqli(): mysqli
    {
        return $this->mysqli;
    }

    /**
     * @param mysqli $mysqli
     *
     * @return $this
     */
    public function setMysqli(mysqli $mysqli)
    {
        $this->mysqli = $mysqli;

        return $this;
    }

}
