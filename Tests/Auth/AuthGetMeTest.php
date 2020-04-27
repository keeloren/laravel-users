<?php

namespace Tests\Feature\Auth;

use NguyenND\Users\Test\TestCaseBase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthGetMeTest extends TestCaseBase
{
    public function tearDown() : void
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
        $res = $this->getJson('/api/users/me', []);
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
        $res        = $this->getJson('/api/users/me', ['HTTP_Authorization' => "Bearer $token"]);
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
