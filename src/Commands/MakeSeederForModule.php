<?php

namespace ModuleGenerator\Commands;

use Illuminate\Database\Console\Seeds\SeederMakeCommand;
use Symfony\Component\Console\Input\InputOption;

class MakeSeederForModule extends SeederMakeCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:seeder';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        parent::handle();
        $name = $this->argument("name");
        $this->info("{$name} seeder created");
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
        $path = $this->getPathForModule($module).$name.".php";
        return $path;
    }

    private function getPathForModule($module) {
        $directory = config("module_generator.workdir"). "modules/{$module}/database/seeders/";
        if (! $this->files->isDirectory($directory)) {
            $this->files->makeDirectory($directory, 0777, true, true);
        }
        return $directory;
    }
}
