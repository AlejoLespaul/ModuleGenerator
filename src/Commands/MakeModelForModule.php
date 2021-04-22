<?php

namespace ModuleGenerator\Commands;

use Illuminate\Foundation\Console\ModelMakeCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class MakeModelForModule extends ModelMakeCommand
{
    use AssertModuleOptions;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'module:model';


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $this->assertModuleOptionExists();
            parent::handle();
            $name = $this->argument("name");
            $this->info("{$name} model created");
        }catch (\Exception $e){
            $this->comment($e->getMessage());
        }
    }

    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);
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
        return config("module_generator.workdir"). "modules/{$module}/Model";
    }

    protected function rootNamespace()
    {
        $rootNamespace = str_replace("/", "\\", $this->option("module"));
        return $rootNamespace;
    }

    protected function getNamespace($name)
    {
        return $this->rootNamespace() . "\Model";
    }

    protected function getEntityName($name)
    {
        return str_replace("\\", "/", $this->rootNamespace() . "\Model\\$name");
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        $class = $this->argument("name");
        return str_replace(['DummyClass', '{{ class }}', '{{class}}'], $class, $stub);
    }
    /**
     * Create a controller for the model.
     *
     * @return void
     */
    protected function createController()
    {
        $controller = Str::studly(class_basename($this->argument('name')));

        $modelName = $this->qualifyClass($this->getNameInput());

        $this->call('module:controller', array_filter([
            'name'  => "{$controller}Controller",
            '--model' => $this->option('resource') || $this->option('api') ? $modelName : null,
            '--api' => $this->option('api'),
            '--module' => $this->option("module")
        ]));
    }

        /**
     * Create a migration file for the model.
     *
     * @return void
     */
    protected function createMigration()
    {
        $table = Str::snake(Str::pluralStudly(class_basename($this->argument('name'))));

        if ($this->option('pivot')) {
            $table = Str::singular($table);
        }

        $this->call('module:migration', [
            'name' => "create_{$table}_table",
            '--create' => $table,
            '--module' => $this->option("module")
        ]);
    }

    /**
     * Create a seeder file for the model.
     *
     * @return void
     */
    protected function createSeeder()
    {
        $seeder = Str::studly(class_basename($this->argument('name')));

        $this->call('module:seeder', [
            'name' => "{$seeder}Seeder",
            '--module' => $this->option("module")
        ]);
    }

        /**
     * Create a model factory for the model.
     *
     * @return void
     */
    protected function createFactory()
    {
        $factory = Str::studly($this->argument('name'));

        $this->call('module:factory', [
            'name' => "{$factory}Factory",
            '--model' => $this->getEntityName($this->getNameInput()),
            '--module' => $this->option("module")
        ]);
    }

}
