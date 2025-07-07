<?php

namespace Pedrocruzj\QuickSettings;

use Illuminate\Support\ServiceProvider;
use Pedrocruzj\QuickSettings\QuickSettings;

class QuickSettingsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('settings', function () {
            return new QuickSettings();
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/migrations/' => $this->app->databasePath('migrations'),
        ], 'quick-settings-migrations');
    }
}
