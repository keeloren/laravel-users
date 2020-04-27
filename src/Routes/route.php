<?php
Route::group(['prefix' => 'api/users'], function () {
    
    Route::group(['namespace' => 'NguyenND\Users\Http\Controllers'], function () {
        Route::post('register', 'AuthController@register');
        Route::post('oauth/token', 'AccessTokenController@issueToken')->name('login');
        Route::post('password/forgot/request', 'ForgotPasswordController@getResetToken');
        Route::post('password/forgot/reset', 'ResetPasswordController@reset');
        Route::group(['middleware' => 'auth:api'], function () {
            Route::get('me', 'AuthController@authenticated');
            Route::post('logout', 'AuthController@logout');
            Route::post('password/change', 'ChangePasswordController@changePassword');
        });
    });
});
