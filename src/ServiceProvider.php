<?php


namespace Owlet\CmbcSign;


class ServiceProvider extends \Illuminate\Support\ServiceProvider
{

    protected $defer = true;

    /**
     * 服务引导方法
     *
     * @return void
     */
    public function boot(): void
    {
        //发布配置文件到项目的 config 目录中
        $this->publishes([
            __DIR__ . '/config/sign.php' => config_path('sign.php'),
        ]);
    }

    /**
     * 注册服务
     */
    public function register(): void
    {
        $this->app->singleton(Cmbc::class, function () {
            return new Cmbc();
        });
    }

}
