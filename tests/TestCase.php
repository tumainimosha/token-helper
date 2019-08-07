<?php

namespace Tumainimosha\TokenHelper\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Tumainimosha\TokenHelper\TokenHelperServiceProvider;

class TestCase extends BaseTestCase
{
    public function setup()
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->artisan('migrate', ['--database' => 'testing']);
        $this->loadLaravelMigrations(['--database' => 'testing']);
        $this->withFactories(__DIR__ . '/../src/database/factories');
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.key', 'AckfSECXIvnK5r28GVIWUAxmbBSjTsmF');
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [TokenHelperServiceProvider::class];
    }
}
