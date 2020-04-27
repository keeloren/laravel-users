<?php

namespace  NguyenND\Users\Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase1;

class AuthForgotPasswordTest1 extends TestCase1
{
    public function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }

    /**
     * Test failed case: forgot pasword with empty request
     *
     * @return void
     */
    public function testForgotPasswordEmptyRequest()
    {
        $payload = [];
        $res     = $this->postJson('/api/password/forgot/request', $payload);
        $res->assertStatus(400);
        $res->assertJson([
            'title'  => 'Validation Error.',
            'errors' => [
                [
                    'detail' => 'The email field is required.'
                ]
            ]
        ]);
    }

    /**
     * Test failed case: forgot password with email invalid format
     *
     * @return void
     */
    public function testForgotPasswordEmailInvalidFormat()
    {
        $payload = [
            'email' => 'example'
        ];
        $res = $this->postJson('/api/password/forgot/request', $payload);
        $res->assertStatus(400);
        $res->assertJson([
            'title'  => 'Validation Error.',
            'errors' => [
                [
                    'detail' => 'The email must be a valid email address.'
                ]
            ]
        ]);
    }

    /**
     * Test failed case: forgot password with user who does not exists
     *
     * @return void
     */
    public function testForgotPasswordWithUserNotExists()
    {
        $payload = [
            'email' => 'example@gmail.com'
        ];
        $res = $this->postJson('/api/password/forgot/request', $payload);
        $res->assertStatus(400);
        $res->assertJson([
            'title'  => 'Validation Error.',
            'errors' => [
                [
                    'detail' => 'The selected email is invalid.'
                ]
            ]
        ]);
    }

    /**
     * Test forgot password successfully
     *
     * @return void
     */
    public function testForgotPasswordSuccess()
    {
        $user    = $this->getUser();
        $payload = [
            'email' => $user->email
        ];
        $res = $this->postJson('/api/password/forgot/request', $payload);
        $res->assertStatus(200);
        $res->assertJson([
            'title' => trans('messages.auth.resetPasswordSuccess')
        ]);
    }
}
