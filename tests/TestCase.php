<?php

namespace Tests;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Myerscode\Laravel\Taxonomies\ServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    use RefreshDatabase;

    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
        ];
    }

    public function setUp(): void
    {
        parent::setUp();
        $this->loadLocales($this->app);
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../src/Stubs/migrations');
        $this->loadMigrationsFrom(__DIR__ . '/Support/migrations');
    }

    public function loadLocales(Application $app)
    {
        $this->app['translator']->addLines([
            'terms.foo-bar' => 'Foo La La',
            'taxonomies.hello-world' => 'Bonjour le monde',
        ], 'fr');

        $this->app['translator']->addLines([
            'terms.foo-bar' => 'Tymor un',
            'taxonomies.hello-world' => 'Helo Byd',
        ], 'cy');
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('taxonomies', [
            'taxonomy' => [
                'model' => \Myerscode\Laravel\Taxonomies\Taxonomy::class,
            ],
            'term' => [
                'model' => \Myerscode\Laravel\Taxonomies\Term::class,
            ],
        ]);
    }
}
