<?php

namespace Omaralalwi\LexiTranslate;

use Illuminate\Support\ServiceProvider;

class LexiTranslateServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        if ($this->app->runningInConsole()) {
            // Publish config file
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('lexi-translate.php'),
            ], 'config');

            // Publish migrations
            $this->publishes([
                __DIR__.'/../database/migrations/' => database_path('migrations'),
            ], 'migrations');
        }
    }

    public function register()
    {
        // Merge default configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'lexi-translate');

        // Register the singleton
        $this->app->singleton('lexi-translate', function () {
            return new LexiTranslate;
        });
    }
}
