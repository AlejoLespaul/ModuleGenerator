<?php

namespace ModuleGenerator\Providers;

use ModuleGenerator\Commands\ModuleProviderCreateCommand;
use Illuminate\Support\ServiceProvider;
use ModuleGenerator\Commands\ModuleCreateCommand;

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
                ModuleCreateCommand::class,
                ModuleProviderCreateCommand::class
            ]);
        }
    }
}
