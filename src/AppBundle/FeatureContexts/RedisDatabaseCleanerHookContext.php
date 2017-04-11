<?php

namespace AppBundle\FeatureContexts;

use Behat\Behat\Context\Context;
use Predis\Client;

class RedisDatabaseCleanerHookContext implements Context
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @BeforeScenario
     */
    public function clearDatabase()
    {
        $this->client->flushdb();
    }
}
