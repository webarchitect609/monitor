<?php

namespace WebArch\Monitor\Test\Service;

use DateInterval;
use DateTimeZone;
use Exception;
use LogicException;
use mysqli;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use ReflectionProperty;
use Throwable;
use WebArch\Monitor\Enum\ErrorCode;
use WebArch\Monitor\Exception\InvalidArgumentException;
use WebArch\Monitor\Exception\InvalidTokenException;
use WebArch\Monitor\Exception\MetricIsNotFoundException;
use WebArch\Monitor\Exception\TokenIsNotConfiguredException;
use WebArch\Monitor\Metric\DummyMySQLiMetric;
use WebArch\Monitor\Service\MonitorBase;
use WebArch\Monitor\Service\MySQLiAwareMonitor;

class MySQLiAwareMonitorTest extends TestCase
{
    /**
     * @var string
     */
    private $token;

    /**
     * @var MockObject|mysqli
     */
    private $mysqli;

    /**
     * @var DateInterval
     */
    private $interval;

    /**
     * @var string
     */
    private $metricName;

    /**
     * @var DummyMySQLiMetric|MockObject
     */
    private $metric;

    /**
     * @var string
     */
    private $mockCalcValue;

    /**
     * @var MySQLiAwareMonitor
     */
    private $monitor;

    /**
     * @var DateTimeZone
     */
    private $timeZone;

    /**
     * @inheritDoc
     * @throws Exception
     */
    protected function setUp(): void
    {
        /**
         * Suppress warning "Cannot modify header information - headers already sent by"
         * and notice "Undefined index"
         */
        $this->iniSet('error_reporting', ini_get('error_reporting') & ~E_WARNING & ~E_NOTICE);
        $this->token = bin2hex(random_bytes(32));
        $_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';
        $_SERVER[MonitorBase::TOKEN_HEADER_KEY] = $this->token;
        $this->mysqli = $this->getMockBuilder(mysqli::class)
                             ->getMock();
        $this->interval = new DateInterval('PT1H');
        $this->metricName = 'mock-metric-name';
        $this->metric = $this->getMockBuilder(DummyMySQLiMetric::class)
                             ->setConstructorArgs([$this->metricName])
                             ->onlyMethods(['calculate'])
                             ->getMock();
        $this->mockCalcValue = 'mock-calc-value';
        $this->metric->method('calculate')
                     ->willReturn($this->mockCalcValue);
        $this->timeZone = new DateTimeZone('GMT+03:00');
        $this->monitor = (new MySQLiAwareMonitor($this->token, $this->mysqli))->setInterval($this->interval)
                                                                              ->setTimeZone($this->timeZone)
                                                                              ->addMetric($this->metric);
    }

    public function testCreation()
    {
        $_SERVER[MonitorBase::TOKEN_HEADER_KEY] = $this->token;
        $this->expectOutputString('');
        $this->assertInstanceOf(
            MySQLiAwareMonitor::class,
            new MySQLiAwareMonitor($this->token, $this->mysqli)
        );
    }

    public function testInvalidToken()
    {
        $_SERVER[MonitorBase::TOKEN_HEADER_KEY] = $this->token . 'shall fail!';
        $this->expectException(InvalidTokenException::class);
        $this->expectExceptionCode(ErrorCode::INVALID_TOKEN);
        new MySQLiAwareMonitor($this->token, $this->mysqli);
    }

    public function testCheckEmptyToken()
    {
        $this->expectException(TokenIsNotConfiguredException::class);
        $this->expectExceptionCode(ErrorCode::TOKEN_IS_NOT_CONFIGURED);
        new MySQLiAwareMonitor('', $this->mysqli);
    }

    public function testSetIntervalGetInterval()
    {
        $this->assertSame(
            $this->interval,
            $this->monitor->getInterval()
        );
    }

    public function testSetTimeZoneGetTimeZone()
    {
        $this->assertSame(
            $this->timeZone,
            $this->monitor->getTimeZone()
        );
        $this->monitor->setTimeZone(null);
        $this->assertNull($this->monitor->getTimeZone());
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function testAddMetric()
    {
        $metricsProperty = new ReflectionProperty($this->monitor, 'metrics');
        $metricsProperty->setAccessible(true);
        $metrics = $metricsProperty->getValue($this->monitor);
        $this->assertArrayHasKey($this->metricName, $metrics);
        $this->assertSame($this->metric, $metrics[$this->metricName]);
    }

    public function testAddMetricFails()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode(ErrorCode::OTHER_ERROR);
        $this->monitor->addMetric($this->metric);
    }

    public function testEvalMetricByName()
    {
        $this->assertSame(
            $this->mockCalcValue,
            $this->monitor->evalMetricByName($this->metricName)
        );
    }

    public function testEvalMetricByNameFails()
    {
        $this->expectException(MetricIsNotFoundException::class);
        $this->expectExceptionCode(ErrorCode::METRIC_IS_NOT_FOUND);
        $this->monitor->evalMetricByName('non-existing-metric-name');
    }

    /**
     * @throws Throwable
     */
    public function testExec()
    {
        $this->assertSame(
            $this->mockCalcValue,
            $this->monitor->exec($this->metricName)
        );
    }

    /**
     * @throws Throwable
     */
    public function testExecFails()
    {
        $this->expectException(MetricIsNotFoundException::class);
        $this->expectExceptionCode(ErrorCode::METRIC_IS_NOT_FOUND);
        $this->monitor->exec('non-existing-metric-name');
    }

    /**
     * @throws Throwable
     */
    public function testExecFailsFromMetricsException()
    {
        $metricName = 'exception-metrics';
        /** @var DummyMySQLiMetric|MockObject $metric */
        $metric = $this->getMockBuilder(DummyMySQLiMetric::class)
                       ->setConstructorArgs([$metricName])
                       ->onlyMethods(['calculate'])
                       ->getMock();
        $exceptionCode = 123;
        $exceptionMessage = 'It was expected!';
        $metric->method('calculate')
               ->willThrowException(new LogicException($exceptionMessage, $exceptionCode));
        $this->monitor->addMetric($metric);
        $this->expectException(LogicException::class);
        $this->expectExceptionCode($exceptionCode);
        $this->expectExceptionMessage($exceptionMessage);
        $this->monitor->exec($metricName);
    }
}
