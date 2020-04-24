<?php

use WebArch\Monitor\Metric\DummyMySQLiMetric;
use WebArch\Monitor\Service\MySQLiAwareMonitor;

/**
 * 1 Require /vendor/autoload.php
 * 2 Require as less configuration from your application as possible. For example, only the file where the MySQL
 * database credentials are saved.
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

/**
 * 3 Specify the token, which must be send in the header X-Monitor-Token.
 * 4 Additionally specify other parameters for the specific type of monitor you use.
 */
$monitor = MySQLiAwareMonitor::create(
    'very-long-token-to-be-placed-here!',
    'localhost',
    'username',
    'password',
    'dbname',
    3306
);

/**
 * 5 Setup the desired interval for which metrics should be calculated.
 */
$monitor->setInterval(new DateInterval('PT1M'));

/**
 * 6 Setup which metrics with which names will be available.
 */
$monitor->addMetric(new DummyMySQLiMetric('dummy-metric'));

/**
 * 7 Specify which parameter the metric's name is given.
 */
echo $monitor->exec(trim($_REQUEST['metric']));

/**
 * 7 Setup Zabbix or similar software for sending HTTP-request with the token and the metric's name. The response body
 * will contain the metric's value only.
 */
