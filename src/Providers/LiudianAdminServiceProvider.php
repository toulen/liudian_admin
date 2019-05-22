<?php
namespace Liudian\Admin\Providers;

use Illuminate\Support\ServiceProvider;
use Liudian\Admin\Http\Middleware\AdminAuth;
use Liudian\Admin\Logic\AdminAuthLogic;
use Liudian\Admin\Logic\ChinaAreaLogic;
use Liudian\Admin\Repositories\AdminRbacPermissionRepository;

class LiudianAdminServiceProvider extends ServiceProvider
{

    protected $routeMiddleware = [
        'admin_auth' => AdminAuth::class
    ];

    protected $middlewareGroups = [

    ];

    public function register(){
        $this->app->singleton('admin_auth', AdminAuthLogic::class);
        $this->app->singleton('china_area', ChinaAreaLogic::class);
        $this->app->singleton('rbac_permission', AdminRbacPermissionRepository::class);
        $this->registerRouteMiddleware();
    }

    public function boot(){
        $this->loadLiudianAdminConfig();
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views/', 'admin');

        if($this->app->runningInConsole()){
            $this->publishes([
                __DIR__ . '/../../config/liudian_admin.php' => config_path('liudian_admin.php'),
                __DIR__ . '/../../public/admin' => public_path('admin'),
                __DIR__ . '/../../database/seeds' => database_path('seeds'),
                __DIR__ . '/../Http/Controllers/IndexController.php' => base_path('liudian/admin/Http/Controllers/IndexController.php')
            ]);
        }
    }

    protected function loadLiudianAdminConfig(){

//        $this->mergeConfigFrom(__DIR__ . '/../../config/liudian_admin.php', 'liudian_admin');
    }

    protected function registerRouteMiddleware()
    {
        // register route middleware.
        foreach ($this->routeMiddleware as $key => $middleware) {
            app('router')->aliasMiddleware($key, $middleware);
        }
        // register middleware group.
        foreach ($this->middlewareGroups as $key => $middleware) {
            app('router')->middlewareGroup($key, $middleware);
        }
    }
}