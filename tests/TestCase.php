<?php

namespace Rickgoemans\LaravelEnumHelpers\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use Rickgoemans\LaravelEnumHelpers\LaravelEnumHelpersServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(fn (string $modelName) => 'Rickgoemans\\LaravelEnumHelpers\\Database\\Factories\\'.class_basename($modelName).'Factory');
    }

    protected function getPackageProviders($app): array
    {
        return [
            LaravelEnumHelpersServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
    }
}
