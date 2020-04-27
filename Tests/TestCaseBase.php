<?php

namespace NguyenND\Users\Test;

use NguyenND\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use NguyenND\Users\Providers\UserServiceProvider;
use Tests\CreatesApplication;
use Orchestra\Testbench\TestCase as TestCaseOrchestra;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
class TestCaseBase extends TestCase
{
    use CreatesApplication;
    use DatabaseMigrations;
    use DatabaseTransactions;
    
    public function setUp(): void
    {
        parent::setUp();
        \Artisan::call('migrate', ['-vvv' => true]);
        \Artisan::call('passport:install', ['-vvv' => true]);
    }
    
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
    public function getUser()
    {
        $user = factory(User::class)->create();
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
