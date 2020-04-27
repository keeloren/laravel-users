<?php

namespace Tests\Feature;

use Tests\TestCase1;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ABC extends TestCase1
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTestA()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
