<?php

define('EXT_NAMESPACE', 'Ext\\Liudian\\Admin\\Http\\Controllers');

define('LIUDIAN_NAMESPACE', 'Liudian\\Admin\\Http\\Controllers');

Route::group(['prefix' => config('liudian_admin.route_prefix'), 'as' => 'admin_'], function (){

    Route::group(['prefix' => 'public', 'as' => 'public_'], function (){
        $controller = getController(EXT_NAMESPACE, LIUDIAN_NAMESPACE, 'PublicController');
        Route::get('captcha', $controller . '@captcha')->name('captcha');
        Route::post('ajax/upload', $controller . '@ajaxUpload')->name('ajax_upload');
        Route::get('no/permission', $controller . '@noPermission')->name('no_permission');
    });

    Route::group([], function (){
        $controller = getController(EXT_NAMESPACE, LIUDIAN_NAMESPACE, 'AuthController');

        Route::match(['get', 'post'], 'login', $controller . '@login')->name('login');
        Route::post('logout', $controller . '@logout')->name('logout');
    });

    Route::group(['middleware' => 'admin_auth'], function (){

        Route::get('/index', getController(EXT_NAMESPACE, LIUDIAN_NAMESPACE, 'IndexController') . '@index')->name('index');

        // 管理员账号
        Route::group(['prefix' => 'account', 'as' => 'account_'], function (){

            $controller = getController(EXT_NAMESPACE, LIUDIAN_NAMESPACE, 'AccountController');

            Route::match(['get', 'post'], 'index', $controller . '@index')->name('index');
            Route::match(['get', 'post'], 'create', $controller . '@create')->name('create');
            Route::match(['get', 'post'], 'edit/password/{id}', $controller . '@editPassword')->name('edit_password');
            Route::match(['get', 'post'], 'edit/{id}', $controller . '@edit')->name('edit');
            Route::post('delete/{id}', $controller . '@delete')->name('delete');

            Route::match(['get','post'], 'roles/{id}', $controller . '@roles')->name('roles');
        });

        // 管理员分组
        Route::group(['prefix' => 'role', 'as' => 'role_'], function (){

            $controller = getController(EXT_NAMESPACE, LIUDIAN_NAMESPACE, 'AdminRoleController');

            Route::match(['get', 'post'], 'index', $controller . '@index')->name('index');
            Route::match(['get', 'post'], 'create', $controller . '@create')->name('create');
            Route::match(['get', 'post'], 'edit/{id}', $controller . '@edit')->name('edit');
            Route::match(['get', 'post'], 'permissions/{id}', $controller . '@permissions')->name('permissions');
            Route::post('delete/{id}', $controller . '@delete')->name('delete');
        });

        // 后台菜单，（RBAC权限）
        Route::group(['prefix' => 'permission', 'as' => 'permission_'], function (){

            $controller = getController(EXT_NAMESPACE, LIUDIAN_NAMESPACE, 'AdminPermissionController');

            Route::match(['get', 'post'], 'index', $controller . '@index')->name('index');
            Route::match(['get', 'post'], 'create', $controller . '@create')->name('create');
            Route::match(['get', 'post'], 'edit/{id}', $controller . '@edit')->name('edit');
            Route::post('delete/{id}', $controller . '@delete')->name('delete');
            Route::post('move/{id}', $controller . '@move')->name('move');
        });
    });
});