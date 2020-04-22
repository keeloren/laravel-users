<?php
Route::group(['prefix' => 'api/users'], function () {
    
    Route::group(['namespace' => 'NguyenND\Users\Http\Controllers'], function () {
//        Route::get('/laravel-user', 'AuthController@index');
        Route::post('register', 'AuthController@register');
        Route::post('oauth/token', 'AccessTokenController@issueToken');
        Route::group(['middleware' => 'auth:api'], function () {
            Route::get('me', 'AuthController@authenticated');
        });
        
        // Customer
//        Route::post('customer/login', 'SessionController@create');
//        Route::post('customer/register', 'CustomerController@create');
//        Route::group(['middleware' => ['jwt.auth']], function () {
//            Route::post('customer/forgot-password', 'ForgotPasswordController@store');
//
//            // Category
//            Route::get('categories', 'ResourceController@index')->defaults('_config', [
//                'repository' => 'Webkul\Category\Repositories\CategoryRepository',
//                'resource' => 'Webkul\API\Http\Resources\Catalog\Category'
//            ]);
//
//            // Card
//            Route::get('/user/cards', 'CardController@cards');
//            Route::get('/user/cards/{id}', 'CardController@showCard');
//            Route::post('/user/cards', 'CardController@addCard');
//            Route::patch('/user/cards/{id}', 'CardController@updateCard');
//
//            // Cart
//            Route::group(['prefix' => 'checkout'], function () {
//                Route::post('cart/add/{id}', 'CartController@store');
//                Route::post('save-address', 'CheckoutController@saveAddressToCart');
//                Route::post('save-shipping', 'CheckoutController@saveShipping');
//                Route::post('save-payment', 'CheckoutController@savePayment');
//            });
//
//            // Payment
//            Route::post('/payment/{orderId}', 'PaymentController@store');
//        });
    });
});
