<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use NguyenND\Users\Models\User;
use Auth;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

abstract class TestCase1 extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseMigrations;
    use DatabaseTransactions;

    /**
     * Get token
     *
     * @param any $credentials
     *
     * @return string $token
     */
    public function getToken($credentials = null)
    {
        if ($credentials && isset($credentials['email'])) {
            $user = User::where('email', $credentials['email'])->first();
        } else {
            $user = factory(User::class)->create();
        }
        $objToken = $user->createToken(config('constants.API.PERSONAL_ACCESS_CLIENT_NAME'));
        $token    = $objToken->accessToken;
        return $token;
    }

    /**
     * Get User
     *
     * @param string $role
     *
     * @return \App\Models\User
     */
    public function getUser($role = null)
    {
        $user = factory(User::class)->create();
        $role && $user->attachRole(Role::firstOrCreate(['name' => $role]));
        return $user;
    }

    /**
     * Get attributes field of response
     *
     * @param \Illumitae\Http\Response $res
     *
     * @return array
     */
    public static function getAttributes($res)
    {
        $data = json_decode($res->getContent());
        return $data->data->attributes ?? [];
    }

    /**
     * Get Id of response
     *
     * @param \Illumitae\Http\Response $res
     *
     * @return string
     */
    public static function getId($res)
    {
        $data = json_decode($res->getContent());
        return $data->data->id ?? '';
    }
}
