<?php

namespace Tests;

use Illuminate\Support\Facades\Config;
use Laravelizer\LaravelizerServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->afterApplicationCreated(function () {
            $this->resetFileSystem();
        });
        $this->beforeApplicationDestroyed(function () {
            $this->resetFileSystem();
        });


        Config::set('database.connections.mysql.database', 'chipperci');
        Config::set('database.connections.mysql.username', 'chipperci');
        Config::set('database.connections.mysql.password', 'secret');

    }

    public function resetFileSystem()
    {
        foreach (['model', 'migration', 'factory', 'nova', 'test'] as $component) {
            Config::set('laravelizer.'.$component.'.path', '/tmp/laravelizer/'.$component);

            $dir = '/tmp/laravelizer/'.$component;
            if (is_dir($dir)) {
                $files = array_diff(scandir($dir), ['.', '..']);
                foreach ($files as $file) {
                    unlink("$dir/$file");
                }
                rmdir($dir);
            }
        }
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelizerServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('view.paths', [__DIR__.'/../resources/views']);
        $app['config']->set('app.key', 'base64:r0w0xC+mYYqjbZhHZ3uk1oH63VadA3RKrMW52OlIDzI=');
    }
}
