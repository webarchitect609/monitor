<?php

namespace WebArch\Monitor\Metric\Interfaces;

use GuzzleHttp\Client;

interface GuzzleClientAwareMetricInterface
{
    public function setClient(Client $client);
}
