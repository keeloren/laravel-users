<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use NguyenND\Users\Test\TestCaseBase;

class AuthRegisterTest extends TestCaseBase
{
    public function tearDown() : void
    {
        \Mockery::close();
        parent::tearDown();
    }

    /**
     * Test failed case: register with empty request
     *
     * @return void
     */
    public function testRegisterEmptyRequest()
    {
        $res = $this->postJson('/api/users/register', []);
        $res->assertStatus(400);
        $res->assertJson([
            'title'  => 'Validation Error.',
            'errors' => [
                [
                    'detail' => 'The email field is required.'
                ],
                [
                    'detail' => 'The password field is required.'
                ],
                [
                    'detail' => 'The name field is required.'
                ],
            ]
        ]);
    }

    /**
     * Test failed case: register with invalid request
     *
     * @return void
     */
    public function testRegisterInvalidRequest()
    {
        $res = $this->postJson('/api/users/register',
            [
                'email' => 'abc@example.com',
                'password' => '12345678',
                'password_confirmation' => '12345678',
                'name' => 12321321,
            ]
        );

        $res->assertStatus(400);
        $res->assertJson([
            'title'  => 'Validation Error.',
            'errors' => [
                [
                    'detail' => 'The name must be a string.'
                ],
            ]
        ]);
    }

    /**
     * Test register successfully
     *
     * @return void
     */
    public function testRegisterSuccess() {
        $payload = [
            'email'                 => 'user@example.com',
            'password'              => '12345678',
            'password_confirmation' => '12345678',
            'name'                  => 'nameTest',
            'birthday'              => '1990-09-09',
            'gender'                => '1',
            'phone'                 => '0908456352',
            'university'            => 'universityTest',
            'department'            => 'departmentTest',
        ];

        $res   = $this->postJson('/api/users/register', $payload);
        $res->assertStatus(200);
        $res->assertJson([
            'title' => trans('lang::messages.auth.registerSuccess'),
            'status' => 200,
        ]);
    }
}
