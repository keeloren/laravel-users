<?php

namespace NguyenND\Users\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as AuthServiceProvider;
use Laravel\Passport\Passport;
use NguyenND\Users\Models\User;
use Config;

class UserServiceProvider extends AuthServiceProvider
{

    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/route.php');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/constants.php', 'constants'
        );
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'lang');
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../Database/migrations');
            $this->publishMigrations();
        }

        $this->registerPolicies();

        // set auth config system
        $setDriver = Config::set('auth.guards.api.driver','passport');
        $setProviders = Config::set('auth.providers.users.model', User::class);

        Passport::routes();
        Passport::tokensExpireIn(now()->addDays(config('constants.TOKEN.REFRESH_TOKEN_EXPIRE_IN')));
        Passport::refreshTokensExpireIn(now()->addDays(config('constants.TOKEN.REFRESH_TOKEN_EXPIRE_IN')));
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    
    }

    private function publishMigrations()
    {
        $path = $this->getMigrationsPath();
        $this->publishes([$path => database_path('migrations')], 'laravel-users-migrations');
    }

    private function getMigrationsPath()
    {
        return __DIR__ . '/../Database/migrations/';
    }
}
