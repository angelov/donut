<?php

namespace Angelov\Donut\Tests\Api;

// @temporary
use ApiTestCase\JsonApiTestCase;

class ApiTestCase extends JsonApiTestCase
{
    public function tearDown() : void
    {
        $this->client = null;

        parent::tearDown();
    }
}
