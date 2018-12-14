<?php

namespace Tests;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Myerscode\Laravel\Taxonomies\ServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
//    use RefreshDatabase;

    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
        ];
    }

    public function setUp()
    {
        parent::setUp();
        $this->createDatabase($this->app);
    }

    public function createDatabase(Application $app)
    {
        $this->loadMigrationsFrom(__DIR__ . '/Support/migrations');
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
