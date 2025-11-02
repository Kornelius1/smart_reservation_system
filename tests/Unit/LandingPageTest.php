<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LandingPageTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $controller = new \App\Http\Controllers\Customer\LandingPageController();

        $response = $controller->index();

        $this->assertEquals('customer.LandingPage', $response->getName());
    }
}