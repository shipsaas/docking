<?php

namespace Tests\Feature;

use Tests\TestCase;

class HealthCheckTest extends TestCase
{
    public function testHealthCheckOk()
    {
        $this->get('/healthz')->isOk();
    }
}
