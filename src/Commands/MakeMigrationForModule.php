<?php

namespace ModuleGenerator\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;
class MakeMigrationForModule extends GeneratorCommand
{
   /**
     * The console command signature.
     *
     * @var string
     */
    protected $name = 'module:migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Migration for Module';


    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        $module = $this->option("module");
        $path = $this->getPathForModule($module);
        return $path;
    }

    private function getPathForModule($module) {
        return config("module_generator.workdir"). "modules/{$module}/database/migrations/";
    }
    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(){}

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $options = [];
        $path = null;

        if($this->option("module")){
            $path = $this->getPath($this->option("module"));
        } else if($this->option("path")){
            $path = $this->option("path");
        }

        if($path){
            $options["--path"] = $path;
        }

        if($this->option("table")){
            $options["--table"] = $this->option("table");
        }
        if($this->option("realpath")){
            $options["--realpath"] = $this->option("realpath");
        }
        if($this->option("fullpath")){
            $options["--fullpath"] = $this->option("fullpath");
        }
        if($this->option("create")){
            $options["--create"] = $this->option("create");
        }
        $name = $this->argument("name");
        $options["name"] = $name;
        $this->call("make:migration", $options);

        $this->info("{$name} migration created");
        $this->comment("Register migrations in boot method provider");
        $this->comment("public function boot()".PHP_EOL.
        "{".PHP_EOL.
        "\t...".PHP_EOL.
        "\t\$this->loadMigrationsFrom(__DIR__.'/../database/migrations');".PHP_EOL.
        "}");
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array_merge( parent::getOptions(), [
            ['--module', 'M', InputOption::VALUE_OPTIONAL, 'Module name to find path.'],
            ['--table', 'T', InputOption::VALUE_OPTIONAL, 'The table to be created'],
            ['--path', 'P', InputOption::VALUE_OPTIONAL, 'The location where the migration file should be created'],
            ['--realpath', 'R', InputOption::VALUE_OPTIONAL, 'Indicate any provided migration file paths are pre-resolved absolute paths'],
            ['--fullpath', 'F', InputOption::VALUE_OPTIONAL, 'Output the full path of the migration'],
            ['--create', 'C', InputOption::VALUE_OPTIONAL, 'Output the full path of the migration'],
        ]);
    }
}
