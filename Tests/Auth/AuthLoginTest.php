<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use NguyenND\Users\Test\TestCaseBase;

class AuthLoginTest extends TestCaseBase
{
    public function tearDown() : void
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
        $res = $this->postJson('/api/users/oauth/token', []);
        $res->assertStatus(400);
    }

    /**
     * Test failed case: login with invalid request
     *
     * @return void
     */
    public function testLoginInvalidRequest()
    {
        $res = $this->postJson('/api/users/oauth/token', ['email' => 'abc@example.com', 'password' => '123123']);
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

        $this->postJson('/api/users/oauth/token', $body, ['Accept' => 'application/json'])
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

        $this->postJson('/api/users/oauth/token', $body, ['Accept' => 'application/json'])
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
        $user = $this->getUser();
        $body = [
            'username'      => $user->email,
            'password'      => 123456789,
            'client_id'     => $oauthClientId,
            'client_secret' => $oauthClient->secret,
            'grant_type'    => 'password',
            'scope'         => '*'
        ];
        
        $res = $this->postJson('/api/users/oauth/token', $body, ['Accept' => 'application/json']);
        $res->assertStatus(400);
        $res->assertJson([
                'error' => 'unsupported_grant_type',
                'message' => 'The authorization grant type is not supported by the authorization server.',
                'hint' => 'Check that all required parameters have been provided',
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
        $user = $this->getUser();
        $body          = [
            'username'      => $user->email,
            'password'      => '12345678',
            'client_id'     => $oauthClientId,
            'client_secret' => $oauthClient->secret,
            'grant_type'    => 'password',
            'scope'         => '*'
        ];
        $this->postJson('/api/users/oauth/token', $body, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure(['token_type', 'expires_in', 'access_token', 'refresh_token']);
    }
}
