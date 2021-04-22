<?php

namespace ModuleGenerator\Commands;

use Illuminate\Foundation\Console\ConsoleMakeCommand;
use Symfony\Component\Console\Input\InputOption;

class MakeCommandForModule extends ConsoleMakeCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'module:command';


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        parent::handle();
        $name = $this->argument("name");
        $this->info("{$name} command created");
        $this->comment("Register command in boot method provider, eg: ");
        $this->comment("public function boot()".PHP_EOL.
        "{".PHP_EOL.
        "\t...".PHP_EOL.
        "\tif (\$this->app->runningInConsole()) {".PHP_EOL.
        "\t\t\$this->commands([".PHP_EOL.
        "\t\t\tExampleCommand::class".PHP_EOL.
        "\t\t]);".PHP_EOL.
        "\t}".PHP_EOL.
        "}");
    }

    protected function getOptions()
    {
        return array_merge(parent::getOptions(), [
            ['--module', 'M', InputOption::VALUE_REQUIRED, 'Module to create the component.'],
        ]);
    }

      /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        $module = $this->option("module");
        $name = $this->argument("name");
        $path = $this->getPathForModule($module)."/{$name}.php";
        return $path;
    }

    private function getPathForModule($module) {
        return config("module_generator.workdir"). "modules/{$module}/Console/Commands";
    }

       /**
     * Parse the class name and format according to the root namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function qualifyClass($name)
    {
        return $name;
    }

     /**
     * Qualify the given model class base name.
     *
     * @param  string  $model
     * @return string
     */
    protected function qualifyModel(string $model)
    {
        $model = ltrim($model, '\\/');

        $model = str_replace('/', '\\', $model);

        return $model;
    }

    /**
     * Get the full namespace for a given class, without the class name.
     *
     * @param  string  $name
     * @return string
     */
    protected function getNamespace($name)
    {
        return str_replace("/", "\\", $this->option("module")). "\\Console\\Commands";
    }
}
