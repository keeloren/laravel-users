<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase1;

class AuthLogoutTest1 extends TestCase1
{
    public function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }

    /**
     * Test logout failed
     *
     * @return void
     */
    public function testLogoutFail()
    {
        $res = $this->postJson('/api/logout', []);
        $res->assertStatus(401);
    }

    /**
     * Test logout successfully
     *
     * @return void
     */
    public function testLogoutSuccess()
    {
        $user  = $this->getUser()->toArray();
        $token = $this->getToken($user);
        $res   = $this->postJson('/api/logout', [], ['HTTP_Authorization' => "Bearer $token"]);
        $res->assertStatus(200);
        $res->assertJsonStructure([
            'status',
            'title',
        ]);
    }
}
