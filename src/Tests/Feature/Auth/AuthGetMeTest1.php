<?php

namespace Tests\Feature\Auth;

use Tests\TestCase1;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthGetMeTest1 extends TestCase1
{
    public function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }
    
    /**
     * Test get me authenticate failed
     *
     * @return void
     */
    public function testGetMeFail()
    {
        $res = $this->getJson('/api/me', []);
        $res->assertStatus(401);
    }
    
    /**
     * Test get me succesfully
     *
     * @return void
     */
    public function testGetMeSuccess()
    {
        $user       = $this->getUser();
        $token      = $this->getToken($user->toArray());
        $res        = $this->getJson('/api/me', ['HTTP_Authorization' => "Bearer $token"]);
        $res->assertStatus(200);
        $res->assertJsonStructure([
            'status',
            'title',
            'data' => [
                'type',
                'id',
                'attributes'
            ],
        ]);
    }
}
