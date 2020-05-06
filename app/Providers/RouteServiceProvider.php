<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * 该名称空间将应用于您的控制器路由。
     *
     * 另外，它被设置为URL生成器的根名称空间。
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * 定义您的路线模型绑定，模式过滤器等。
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * 定义应用程序的路由。
     *
     * @return void
     */
    public function map()
    {
        // admin后台路由
        $this->mapAdminRoutes();
        // 默认web自带路由
        $this->mapWebRoutes();

        //
    }

    /**
     * 定义应用程序的“网络”路由。
     *
     * 这些路由都接收会话状态，CSRF保护等。
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * admin后台路由
     */
    protected function mapAdminRoutes()
    {
        Route::prefix('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/admin.php'));
    }
}
