<?php

Route::group(['prefix' => config('liudian_admin.route_prefix'), 'as' => 'admin_'], function (){

    Route::group(['prefix' => 'public', 'as' => 'public_'], function (){
        Route::get('captcha', 'PublicController@captcha')->name('captcha');
        Route::post('ajax/upload', 'PublicController@ajaxUpload')->name('ajax_upload');
        Route::get('no/permission', 'PublicController@noPermission')->name('no_permission');
    });

    Route::match(['get', 'post'], 'login', 'AuthController@login')->name('login');

    Route::post('logout', 'AuthController@logout')->name('logout');

    Route::group(['middleware' => 'admin_auth'], function (){

        Route::get('/index', 'IndexController@index')->name('index');

        // 管理员账号
        Route::group(['prefix' => 'account', 'as' => 'account_'], function (){
            Route::match(['get', 'post'], 'index', 'AccountController@index')->name('index');
            Route::match(['get', 'post'], 'create', 'AccountController@create')->name('create');
            Route::match(['get', 'post'], 'edit/password/{id}', 'AccountController@editPassword')->name('edit_password');
            Route::match(['get', 'post'], 'edit/{id}', 'AccountController@edit')->name('edit');
            Route::post('delete/{id}', 'AccountController@delete')->name('delete');

            Route::match(['get','post'], 'roles/{id}', 'AccountController@roles')->name('roles');
        });

        // 管理员分组
        Route::group(['prefix' => 'role', 'as' => 'role_'], function (){

            Route::match(['get', 'post'], 'index', 'AdminRoleController@index')->name('index');
            Route::match(['get', 'post'], 'create', 'AdminRoleController@create')->name('create');
            Route::match(['get', 'post'], 'edit/{id}', 'AdminRoleController@edit')->name('edit');
            Route::match(['get', 'post'], 'permissions/{id}', 'AdminRoleController@permissions')->name('permissions');
            Route::post('delete/{id}', 'AdminRoleController@delete')->name('delete');
        });

        // 后台菜单，（RBAC权限）
        Route::group(['prefix' => 'permission', 'as' => 'permission_'], function (){

            Route::match(['get', 'post'], 'index', 'AdminPermissionController@index')->name('index');
            Route::match(['get', 'post'], 'create', 'AdminPermissionController@create')->name('create');
            Route::match(['get', 'post'], 'edit/{id}', 'AdminPermissionController@edit')->name('edit');
            Route::post('delete/{id}', 'AdminPermissionController@delete')->name('delete');
            Route::post('move/{id}', 'AdminPermissionController@move')->name('move');
        });
    });
});