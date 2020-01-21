<?php

namespace Laravelizer;

use Doctrine\DBAL\Types\Type;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Laravelizer\Command\Laravelize;


class LaravelizerServiceProvider extends ServiceProvider
{

    public function boot(): void
    {


        $this->loadViewsFrom('/../resource/views', 'laravelizer');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('laravelizer.php'),
            ], 'config');

            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/laravelizer'),
            ], 'views');

            $this->commands([
                Laravelize::class,
            ]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'laravelizer');

    }

}