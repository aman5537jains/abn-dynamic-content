<?php

namespace Aman5537jains\AbnDynamicContentPlugin;

use Aman5537jains\AbnCms\Lib\AbnCms;
use Illuminate\Support\ServiceProvider;

class AbnDynamicContentPluginServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/views', 'AbnDynamicContent');

        // if(AbnCms::isPluginActive("Aman5537jains\AbnDynamicContentPlugin\AbnDynamicContentPlugin")){
            $this->loadRoutesFrom(__DIR__.'/routes/web.php');
            $this->loadMigrationsFrom(__DIR__.'/migrations');
        // }
    }



    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
                __DIR__.'/dynamic_content.php',
                'dynamic_content'
            );
    }
}
