<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AuthRegisterTest extends TestCase
{
    public function tearDown()
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
        $res = $this->postJson('/api/register', []);
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
        $res = $this->postJson('/api/register',
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
     * Test register wrong format birthday
     *
     * @return void
     */
    public function testRegisterWrongBirthdayFormat()
    {
        $payload = [
            'email'                 => 'user@example.com',
            'password'              => '12345678',
            'password_confirmation' => '12345678',
            'name'                  => 'nameTest',
            'birthday'              => '09-09-1990',
            'gender'                => '1',
            'phone'                 => '0908456352',
            'university'            => 'universityTest',
            'department'            => 'departmentTest',
        ];

        $res = $this->postJson('/api/register', $payload);
        $res->assertStatus(400);
        $res->assertJson([
            'title'  => 'Validation Error.',
            'errors' => [
                [
                    'detail' => 'The birthday does not match the format Y-m-d.'
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

        $res   = $this->postJson('/api/register', $payload);
        $res->assertStatus(200);
        $res->assertJson([
            'title' => trans('messages.auth.registerSuccess'),
            'type'  => 'Token',
        ]);
    }
}
