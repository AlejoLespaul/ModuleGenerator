<?php

namespace ModuleGenerator\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Filesystem\Filesystem;

class ModuleRouteCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:routes {name} {--module}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create routes file for module';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        $relativePath = '/stubs/routes.stub';

        return file_exists($customPath = $this->laravel->basePath(trim($relativePath, '/')))
            ? $customPath
            : __DIR__.$relativePath;
    }

    protected function getPath($name)
    {
        $fileName = $this->argument('name');
        $module = $this->option("module");
        $path = config("module_generator.workdir") . "modules/". $module . "/{$fileName}.php";
        return $path;
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
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());
        $module = $this->option("module");
        return $this->replaceModuleName($stub, $module);
    }

    private function replaceModuleName($stub, $module){
        return str_replace(["{module}", "{route}"], [$module, strtolower($module)], $stub);
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        parent::handle();
        $name = $this->argument('name');
        $this->info("File {$name}.php created");
        $this->comment("Add routes in boot module provider method, e.g.: ");
        $this->comment("public function boot()
        {
            ...".
            '$this->loadRoutesFrom(__DIR__.\'/../'.$name.".php\');
        }");
    }
}
