<?php

namespace TestMonitor\CalculatedColumns\Test;

use Illuminate\Foundation\Application;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use TestMonitor\CalculatedColumns\CalculatedColumnsServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            CalculatedColumnsServiceProvider::class,
        ];
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase($this->app);

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'TestMonitor\\CalculatedColumns\\Database\\Factories\\' . class_basename($modelName) . 'Factory'
        );
    }

    protected function setUpDatabase(Application $app)
    {
        $builder = $app['db']->connection()->getSchemaBuilder();

        $builder->create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });

        $builder->create('order_items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description');
            $table->integer('amount')->default(1);
            $table->bigInteger('price')->default(0);
            $table->integer('order_id');
        });
    }
}
