<?php

namespace ModuleGenerator\Providers;

use Illuminate\Support\ServiceProvider;
use ModuleGenerator\Commands\ModuleCommand;
use ModuleGenerator\Commands\ModuleControllerCommand;
use ModuleGenerator\Commands\ModuleModelCommand;
use ModuleGenerator\Commands\ModuleProviderCommand;
use ModuleGenerator\Commands\ModuleRouteCommand;
use ModuleGenerator\Commands\ModuleTestCommand;

class ModuleGeneratorProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../Config/module_generator.php', 'module_generator'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ModuleCommand::class,
                ModuleProviderCommand::class,
                ModuleRouteCommand::class,
                ModuleTestCommand::class,
                ModuleControllerCommand::class,
                ModuleModelCommand::class
            ]);
        }
    }
}
