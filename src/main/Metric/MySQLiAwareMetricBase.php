<?php

namespace WebArch\Monitor\Metric;

use RuntimeException;
use WebArch\Monitor\Metric\Interfaces\MySQLiAwareMetricInterface;
use WebArch\Monitor\Metric\Traits\MySQLiAwareMetricTrait;

abstract class MySQLiAwareMetricBase extends MetricBase implements MySQLiAwareMetricInterface
{
    use MySQLiAwareMetricTrait;

    /**
     * Returns the calculation of the simple metrics, consisting of SQL query and result in one column.
     *
     * @param string $query
     * @param string $colName
     *
     * @throws RuntimeException
     * @return mixed
     */
    protected function calculateSimpleSqlMetric(string $query, string $colName)
    {
        $result = $this->getMysqli()
                       ->query($query);
        if (false === $result) {
            throw new RuntimeException(
                sprintf(
                    'Error executing query (%s): %s',
                    $this->getMysqli()->errno,
                    $this->getMysqli()->error
                )
            );
        }
        $row = $result->fetch_assoc();
        $result->free();

        if (!array_key_exists($colName, $row)) {
            throw new RuntimeException(
                sprintf(
                    'Column `%s` is not found in the query result for metric %s',
                    $colName,
                    static::class
                )
            );
        }

        return $row[$colName];
    }
}
