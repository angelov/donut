<?php

namespace Angelov\Donut\Tests\Api;

// @temporary
use Lakion\ApiTestCase\JsonApiTestCase;

class ApiTestCase extends JsonApiTestCase
{
    public function tearDown()
    {
        $this->client = null;

        parent::tearDown();
    }
}
