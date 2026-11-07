<?php

namespace Karacraft\Logman;

use Illuminate\Support\ServiceProvider;

class LogmanServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //  Routes
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        //  Views
        $this->loadViewsFrom(__DIR__.'/../resources/views','logman');
        //  Allow Resource Publishing
        if( $this->app->runningInConsole()){
            $this->publishResources();
        }
    }

    public function publishResources()
    {
        //  Publish Migrations
        $this->publishes([
            __DIR__ . '/../database/migrations/create_event_logger_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_event_logger_table.php'),
        ],'logman-migrations');
    }
}
