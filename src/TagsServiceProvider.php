<?php

namespace Spatie\Tags;

use Illuminate\Support\ServiceProvider;
use Spatie\Tags\Console\TruncateExpiredObjects;

class TagsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            if (! class_exists('CreateTagTables')) {
                $timestamp = date('Y_m_d_His', time());
                $this->publishes([
                    __DIR__.'/../database/migrations/create_tag_tables.php.stub' => database_path('migrations/'.$timestamp.'_create_tag_tables.php'),
                ], 'migrations');
            }

            $this->publishes([
                __DIR__.'/../config/tags.php' => config_path('tags.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->bind('command.truncateExpiredObjects', TruncateExpiredObjects::class);

        $this->commands([
            'command.truncateExpiredObjects',
        ]);
    }
}
