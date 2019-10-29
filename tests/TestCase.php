<?php

namespace Spatie\Tags\Test;

use Dotenv\Dotenv;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\Tags\TagsServiceProvider;

abstract class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase($this->app);
    }

    protected function getPackageProviders($app)
    {
        return [
            TagsServiceProvider::class,
        ];
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        //If we're not in travis, load our local .env file
        if (empty(getenv('CI'))) {
            $dotenv = Dotenv::create(realpath(__DIR__.'/..'));
            $dotenv->load();
        }

        $app['config']->set('database.default', 'mysql');
        $app['config']->set('database.connections.mysql', [
            'driver' => 'mysql',
            'host' => '127.0.0.1',
            'database' => env('DB_DATABASE', 'laravel_tags'),
            'username' => env('DB_USERNAME', 'username'),
            'password' => env('DB_PASSWORD', 'password'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => false,
        ]);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpDatabase($app)
    {
        Schema::dropAllTables();

        include_once __DIR__.'/../database/migrations/create_tag_tables.php.stub';

        (new \CreateTagTables())->up();

        $app['db']->connection()->getSchemaBuilder()->create('test_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
        });

        $app['db']->connection()->getSchemaBuilder()->create('test_another_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
        });
    }
}
