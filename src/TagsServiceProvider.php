<?php

namespace Spatie\Tags;

use Illuminate\Support\ServiceProvider;

class TagsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
               __DIR__.'/../config/laravel-tags.php' => config_path('laravel-tags.php'),
           ], 'config');

            if (! class_exists('CreateTagTables')) {
                $timestamp = date('Y_m_d_His', time());
                $this->publishes([
                    __DIR__.'/../database/migrations/_create_tag_tables.php.stub' => database_path('migrations/'.$timestamp.'_create_tag_tables.php'),
                ], 'migrations');
            }
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-tags.php', 'laravel-tags');
    }
}
