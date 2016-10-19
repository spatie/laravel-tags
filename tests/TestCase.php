<?php

namespace Spatie\Tags\Test;

use DB;
use File;
use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\EloquentSortable\SortableServiceProvider;
use Spatie\Tags\TagsServiceProvider;
use Spatie\Translatable\TranslatableServiceProvider;

abstract class TestCase extends Orchestra
{

    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase($this->app);

    }

    protected function getPackageProviders($app)
    {
        return [
            TagsServiceProvider::class,
            TranslatableServiceProvider::class,
            SortableServiceProvider::class
        ];
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'database'  => 'laravel_tags',
            'username'  => env('DB_USERNAME', 'root'),
            'password'  => env('DB_PASSWORD', ''),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'strict'    => false,
        ]);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpDatabase($app)
    {
        $this->dropAllTables();

        include_once __DIR__ . '/../database/migrations/create_tag_tables.php.stub';

        (new \CreateTagTables())->up();

        $app['db']->connection()->getSchemaBuilder()->create('test_models', function (Blueprint $table) {
            $table->increments('id');
            $table->text('name')->nullable();
        });
    }

    protected function dropAllTables()
    {
        $colname = 'Tables_in_laravel_tags';

        $tables = DB::select('SHOW TABLES');

        if(! count($tables)) {
            return;
        }

        foreach($tables as $table) {

            $dropList[] = $table->$colname;

        }
        $dropList = implode(',', $dropList);

        DB::beginTransaction();
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::statement("DROP TABLE $dropList");
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        DB::commit();
    }
}
