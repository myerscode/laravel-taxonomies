<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Myerscode\Laravel\Taxonomies\ServiceProvider;
use Myerscode\Laravel\Taxonomies\Taxonomy;
use Myerscode\Laravel\Taxonomies\Term;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->loadLocales($this->app);
    }

    public function loadLocales(Application $application): void
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

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../src/Stubs/migrations');
        $this->loadMigrationsFrom(__DIR__ . '/Support/migrations');
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('taxonomies', [
            'taxonomy' => [
                'model' => Taxonomy::class,
            ],
            'term' => [
                'model' => Term::class,
            ],
        ]);
    }

    protected function getPackageProviders($app): array
    {
        return [
            ServiceProvider::class,
        ];
    }
}
