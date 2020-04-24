<?php

namespace WebArch\Monitor\Metric\Traits;

use GuzzleHttp\Client;

trait GuzzleClientAwareMetricTrait
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @return Client
     */
    protected function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @param Client $client
     *
     * @return $this
     */
    public function setClient(Client $client)
    {
        $this->client = $client;

        return $this;
    }

}
