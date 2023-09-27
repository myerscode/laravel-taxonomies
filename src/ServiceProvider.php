<?php

namespace Myerscode\Laravel\Taxonomies;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $timestamp = date('Y_m_d_', time());
            $this->publishes([
                __DIR__ . '/Stubs/migrations/0000_00_00_000001_create_taxonomies_table.php' => database_path('migrations/' . $timestamp . '000001_create_taxonomies_table.php'),
                __DIR__ . '/Stubs/migrations/0000_00_00_000002_create_terms_table.php' => database_path('migrations/' . $timestamp . '000002_create_terms_table.php'),
                __DIR__ . '/Stubs/migrations/0000_00_00_000003_create_taggables_table.php' => database_path('migrations/' . $timestamp . '000003_create_taggables_table.php'),
            ], 'migrations');
            $this->publishes([__DIR__ . '/Stubs/config.php' => config_path('taxonomies.php')], 'config');
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/Stubs/config.php', 'taxonomies'
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            //
        ];
    }
}
