<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase1;

class AuthLoginTest1 extends TestCase1
{
    public function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }

    /**
     * Test failed case: login with empty request
     *
     * @return void
     */
    public function testLoginEmptyRequest()
    {
        $res = $this->postJson('/api/oauth/token', []);
        $res->assertStatus(400);
    }

    /**
     * Test failed case: login with invalid request
     *
     * @return void
     */
    public function testLoginInvalidRequest()
    {
        $res = $this->postJson('/api/oauth/token', ['email' => 'abc@example.com', 'password' => '123123']);
        $res->assertStatus(400);
        $res->assertJsonStructure(['error', 'message', 'hint']);
    }

    /**
     * Test failed case: login with the user credentials were incorrect.
     *
     * @return void
     */
    public function testLoginFailed()
    {
        $oauthClientId = env('PASSPORT_CLIENT_ID', 2);
        $oauthClient   = DB::table('oauth_clients')->where('id', $oauthClientId)->first();
        $body          = [
            'username'      => 'test@example.com',
            'password'      => '12345678',
            'client_id'     => $oauthClientId,
            'client_secret' => $oauthClient->secret,
            'grant_type'    => 'password',
            'scope'         => '*'
        ];

        $this->postJson('/api/oauth/token', $body, ['Accept' => 'application/json'])
            ->assertStatus(401)
            ->assertJson([
                'title'  => 'Invalid_credentials',
                'errors' => [
                    [
                        'detail' => 'User does not exist. Please try again'
                    ]
                ]
            ]);
    }

    /**
     * Test failed case: login with the user not found
     *
     * @return void
     */
    public function testLoginFailedWithUserNotFound()
    {
        $oauthClientId = env('PASSPORT_CLIENT_ID', 2);
        $oauthClient = DB::table('oauth_clients')->where('id', $oauthClientId)->first();
        $body = [
            'username'      => 'testTEST@example.com',
            'password'      => '12345678',
            'client_id'     => $oauthClientId,
            'client_secret' => $oauthClient->secret,
            'grant_type'    => 'password',
            'scope'         => '*'
        ];

        $this->postJson('/api/oauth/token', $body, ['Accept' => 'application/json'])
            ->assertStatus(401)
            ->assertJson([
                'title'  => 'Invalid_credentials',
                'errors' => [
                    [
                        'detail' => 'User does not exist. Please try again'
                    ]
                ]
            ]);
    }

    /**
     * Test failed case: login with the user not found
     *
     * @return void
     */
    public function testLoginFailIncorrectPassword()
    {
        $oauthClientId = env('PASSPORT_CLIENT_ID', 2);
        $oauthClient = DB::table('oauth_clients')->where('id', $oauthClientId)->first();
        $body = [
            'username'      => 'admin@example.com',
            'password'      => '12345678222',
            'client_id'     => $oauthClientId,
            'client_secret' => $oauthClient->secret,
            'grant_type'    => 'password',
            'scope'         => '*'
        ];

        $this->postJson('/api/oauth/token', $body, ['Accept' => 'application/json'])
            ->assertStatus(401)
            ->assertJson([
                'title'  => 'Invalid_credentials',
                'errors' => [
                    [
                        'detail' => 'Password is not correct'
                    ]
                ]
            ]);
    }

    /**
     * Test login successfully
     *
     * @return void
     */
    public function testLoginSuccess()
    {
        $oauthClientId = env('PASSPORT_CLIENT_ID', 2);

        $oauthClient   = DB::table('oauth_clients')->where('id', $oauthClientId)->first();
        $body          = [
            'username'      => 'admin@example.com',
            'password'      => '12345678',
            'client_id'     => $oauthClientId,
            'client_secret' => $oauthClient->secret,
            'grant_type'    => 'password',
            'scope'         => '*'
        ];
        $this->postJson('/api/oauth/token', $body, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure(['token_type', 'expires_in', 'access_token', 'refresh_token']);
    }
}
