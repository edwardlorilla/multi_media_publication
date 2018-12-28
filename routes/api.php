<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware(['auth:api'])->group(function () {
    Route::get('/users/notification', 'UserController@notification');
    Route::get('/user/notification/{id}', 'UserController@notificationMarkAsRead');
    Route::get('/products/search', 'ProductController@search');
    Route::get('/products/{product}/subscriptions', 'ProductController@subscribe');
    Route::delete('/products/{product}/subscriptions', 'ProductController@unsubscribe');
    Route::apiResources([
        '/articles' => 'ArticleController',
        '/books' => 'BookController',
        '/documents' => 'DocumentController',
        '/products' => 'ProductController',
    ]);
});