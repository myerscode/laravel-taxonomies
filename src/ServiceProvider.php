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
            $timestamp = date('Y_m_d_His', time());
            $this->publishes([
                __DIR__ . '/Stubs/create_terms_tables.php' => database_path('migrations/' . $timestamp . '_create_taxonomies_table.php'),
                __DIR__ . '/Stubs/create_terms_tables.php' => database_path('migrations/' . $timestamp . '_create_terms_table.php'),
                __DIR__ . '/Stubs/create_terms_tables.php' => database_path('migrations/' . $timestamp . '_create_taggables_table.php'),
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
