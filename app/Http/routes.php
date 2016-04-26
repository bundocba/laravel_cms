<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the controller to call when that URI is requested.
  |
 */

Route::get('/', function () {
    return view('welcome');
});
Route::group(['prefix' => 'admin'], function() {
    // Password reset routes...
    Route::get('password/reset/{token}', ['as' => 'admin.getReset', 'uses' => 'inside\Auth\PasswordController@actionGetReset']);
    Route::post('password/reset', ['as' => 'admin.postReset', 'uses' => 'inside\Auth\PasswordController@actionPostReset']);

    Route::get('password/email', ['as' => 'admin.getEmail', 'uses' => 'inside\Auth\PasswordController@actionGetEmail']);
    Route::post('password/email', ['as' => 'admin.postEmail', 'uses' => 'inside\Auth\PasswordController@actionPostEmail']);
    Route::get('logout', ['as' => 'admin.getLogout', 'uses' => 'inside\Auth\AuthController@actionGetLogout']);
    Route::get('login', ['as' => 'admin.getLogin', 'uses' => 'inside\Auth\AuthController@actionGetLogin']);
    Route::post('login', ['as' => 'admin.postLogin', 'uses' => 'inside\Auth\AuthController@actionPostLogin']);
});

Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function() {
    Route::get('/', ['as' => 'admin.index', 'uses' => 'inside\IndexController@actionIndex']);
    Route::group(['prefix' => 'auth-permissions'], function() {
        Route::get('list', ['as' => 'admin.auth-permissions.getListPermission', 'uses' => 'inside\AuthPermissionController@actionGetList']);

        Route::get('edit/{id}', ['as' => 'admin.auth-permissions.getEditPermission', 'uses' => 'inside\AuthPermissionController@actionGetEdit']);
        Route::post('edit/{id}', ['as' => 'admin.auth-permissions.postEditPermission', 'uses' => 'inside\AuthPermissionController@actionPostEdit']);

        Route::get('permission/{id}', ['as' => 'admin.auth-permissions.getPermission', 'uses' => 'inside\AuthPermissionController@actionGetPermission']);
        Route::post('permission/{id}', ['as' => 'admin.auth-permissions.postPermission', 'uses' => 'inside\AuthPermissionController@actionPostPermission']);
    });
    Route::group(['prefix' => 'user-groups'], function() {

        Route::get('list', ['as' => 'admin.user-groups.getList', 'uses' => 'inside\UserGroupController@actionGetList']);
        Route::get('trash', ['as' => 'admin.user-groups.getTrash', 'uses' => 'inside\UserGroupController@actionGetList']);

        Route::get('add', ['as' => 'admin.user-groups.getAdd', 'uses' => 'inside\UserGroupController@actionGetAdd']);
        Route::post('add', ['as' => 'admin.user-groups.postAdd', 'uses' => 'inside\UserGroupController@actionPostAdd']);

        Route::get('edit/{id}', ['as' => 'admin.user-groups.getEdit', 'uses' => 'inside\UserGroupController@actionGetEdit']);
        Route::post('edit/{id}', ['as' => 'admin.user-groups.postEdit', 'uses' => 'inside\UserGroupController@actionPostEdit']);

        Route::get('inactive/{id}', ['as' => 'admin.user-groups.inactive', 'uses' => 'inside\UserGroupController@actionSetDeleted']);
        Route::get('undo/{id}', ['as' => 'admin.user-groups.undo', 'uses' => 'inside\UserGroupController@actionSetDeleted']);
    });
    Route::group(['prefix' => 'users'], function() {

        Route::get('list', ['as' => 'admin.users.getList', 'uses' => 'inside\UserController@actionGetList']);
        Route::get('trash', ['as' => 'admin.users.getTrash', 'uses' => 'inside\UserController@actionGetList']);

        Route::get('add', ['as' => 'admin.users.getAdd', 'uses' => 'inside\UserController@actionGetAdd']);
        Route::post('add', ['as' => 'admin.users.postAdd', 'uses' => 'inside\UserController@actionPostAdd']);

        Route::get('edit/{id}', ['as' => 'admin.users.getEdit', 'uses' => 'inside\UserController@actionGetEdit']);
        Route::post('edit/{id}', ['as' => 'admin.users.postEdit', 'uses' => 'inside\UserController@actionPostEdit']);

        Route::get('inactive/{id}', ['as' => 'admin.users.inactive', 'uses' => 'inside\UserController@actionSetDeleted']);
        Route::get('undo/{id}', ['as' => 'admin.users.undo', 'uses' => 'inside\UserController@actionSetDeleted']);
    });
    Route::group(['prefix' => 'posts'], function() {

        Route::get('list/{lang?}/{category?}', ['as' => 'admin.posts.getList', 'uses' => 'inside\PostController@actionGetList']);
        Route::get('trash/{lang?}/{category?}', ['as' => 'admin.posts.getTrash', 'uses' => 'inside\PostController@actionGetList']);

        Route::get('add', ['as' => 'admin.posts.getAdd', 'uses' => 'inside\PostController@actionGetAdd']);
        Route::post('add', ['as' => 'admin.posts.postAdd', 'uses' => 'inside\PostController@actionPostAdd']);

        Route::get('edit/{id}', ['as' => 'admin.posts.getEdit', 'uses' => 'inside\PostController@actionGetEdit']);
        Route::post('edit/{id}', ['as' => 'admin.posts.postEdit', 'uses' => 'inside\PostController@actionPostEdit']);

        Route::get('inactive/{id}', ['as' => 'admin.posts.inactive', 'uses' => 'inside\PostController@actionSetDeleted']);
        Route::get('undo/{id}', ['as' => 'admin.posts.undo', 'uses' => 'inside\PostController@actionSetDeleted']);
    });
    Route::group(['prefix' => 'post-categories'], function() {

        Route::get('list/{lang?}/{category?}', ['as' => 'admin.post-categories.getList', 'uses' => 'inside\PostCategoryController@actionGetList']);
        Route::get('trash/{lang?}/{category?}', ['as' => 'admin.post-categories.getTrash', 'uses' => 'inside\PostCategoryController@actionGetList']);

        Route::get('add', ['as' => 'admin.post-categories.getAdd', 'uses' => 'inside\PostCategoryController@actionGetAdd']);
        Route::post('add', ['as' => 'admin.post-categories.postAdd', 'uses' => 'inside\PostCategoryController@actionPostAdd']);

        Route::get('edit/{id}', ['as' => 'admin.post-categories.getEdit', 'uses' => 'inside\PostCategoryController@actionGetEdit']);
        Route::post('edit/{id}', ['as' => 'admin.post-categories.postEdit', 'uses' => 'inside\PostCategoryController@actionPostEdit']);

        Route::get('inactive/{id}', ['as' => 'admin.post-categories.inactive', 'uses' => 'inside\PostCategoryController@actionSetDeleted']);
        Route::get('undo/{id}', ['as' => 'admin.post-categories.undo', 'uses' => 'inside\PostCategoryController@actionSetDeleted']);

        Route::get('deleted/{id}', ['as' => 'admin.post-categories.postDeleted', 'uses' => 'inside\PostCategoryController@actionPostDeleted']);
    });
    Route::group(['prefix' => 'banners'], function() {

        Route::get('list', ['as' => 'admin.banners.getList', 'uses' => 'inside\BannerController@actionGetList']);
        Route::get('trash', ['as' => 'admin.banners.getTrash', 'uses' => 'inside\BannerController@actionGetList']);

        Route::get('add', ['as' => 'admin.banners.getAdd', 'uses' => 'inside\BannerController@actionGetAdd']);
        Route::post('add', ['as' => 'admin.banners.postAdd', 'uses' => 'inside\BannerController@actionPostAdd']);

        Route::get('edit/{id}', ['as' => 'admin.banners.getEdit', 'uses' => 'inside\BannerController@actionGetEdit']);
        Route::post('edit/{id}', ['as' => 'admin.banners.postEdit', 'uses' => 'inside\BannerController@actionPostEdit']);

        Route::get('inactive/{id}', ['as' => 'admin.banners.inactive', 'uses' => 'inside\BannerController@actionSetDeleted']);
        Route::get('undo/{id}', ['as' => 'admin.banners.undo', 'uses' => 'inside\BannerController@actionSetDeleted']);
    });
    Route::group(['prefix' => 'customer-contacts'], function() {
        Route::get('list', ['as' => 'admin.customer-contacts.getList', 'uses' => 'inside\CustomerContactsController@actionGetList']);
        Route::get('trash', ['as' => 'admin.customer-contacts.getTrash', 'uses' => 'inside\CustomerContactsController@actionGetList']);

        Route::get('add', ['as' => 'admin.customer-contacts.getAdd', 'uses' => 'inside\CustomerContactsController@actionGetAdd']);
        Route::post('add', ['as' => 'admin.customer-contacts.postAdd', 'uses' => 'inside\CustomerContactsController@actionPostAdd']);

        Route::get('edit/{id}', ['as' => 'admin.customer-contacts.getEdit', 'uses' => 'inside\CustomerContactsController@actionGetEdit']);
        Route::post('edit/{id}', ['as' => 'admin.customer-contacts.postEdit', 'uses' => 'inside\CustomerContactsController@actionPostEdit']);

        Route::get('inactive/{id}', ['as' => 'admin.customer-contacts.inactive', 'uses' => 'inside\CustomerContactsController@actionSetDeleted']);
        Route::get('undo/{id}', ['as' => 'admin.customer-contacts.undo', 'uses' => 'inside\CustomerContactsController@actionSetDeleted']);
    });
});

