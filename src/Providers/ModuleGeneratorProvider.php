<?php

namespace ModuleGenerator\Providers;

use Illuminate\Support\ServiceProvider;
use ModuleGenerator\Commands\AddModule;
use ModuleGenerator\Commands\MakeControllerForModule;
use ModuleGenerator\Commands\MakeFactoryForModule;
use ModuleGenerator\Commands\MakeMiddlewareForModule;
use ModuleGenerator\Commands\MakeMigrationForModule;
use ModuleGenerator\Commands\MakeModelForModule;
use ModuleGenerator\Commands\MakePolicyForModule;
use ModuleGenerator\Commands\MakeProviderForModule;
use ModuleGenerator\Commands\MakeRoutesForModule;
use ModuleGenerator\Commands\MakeTestForModule;
use ModuleGenerator\Commands\MakeSeederForModule;

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
                AddModule::class,
                MakeProviderForModule::class,
                MakeRoutesForModule::class,
                MakeTestForModule::class,
                MakeControllerForModule::class,
                MakeModelForModule::class,
                MakeMigrationForModule::class,
                MakeSeederForModule::class,
                MakeFactoryForModule::class,
                MakePolicyForModule::class,
                MakeMiddlewareForModule::class
            ]);
        }
    }
}
