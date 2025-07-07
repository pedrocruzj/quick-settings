<?php

namespace Tests;

use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            \Pedrocruzj\QuickSettings\QuickSettingsServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        $app['db']->connection()->getSchemaBuilder()->create('quick_settings', function ($table) {
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }
}
