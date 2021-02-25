<?php

namespace ModuleGenerator\Commands;

use Illuminate\Foundation\Console\ProviderMakeCommand;
use Laminas\Code\Generator\FileGenerator;
use Laminas\Code\Generator\ValueGenerator;

class ModuleProviderCommand extends ProviderMakeCommand
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:provider {name} {--module=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create provider for module';

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        $module = $this->option("module");
        return $module.'\Providers';
    }

    protected function getPath($name)
    {
        $module = $this->option("module");
        $path = config("module_generator.workdir") . "modules/". $this->getModuleFolder($module) . "/Providers/". $name . ".php";

        return $path;
    }

    private function getModuleFolder($module){
        return $module;
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

    protected function getNamespace($name)
    {
        $module = $this->option("module");
        return $this->getNamespaceForModule($module) . "\\Providers";
    }

    private function getNamespaceForModule($module) {
        return str_replace("/", "\\", $module);
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        parent::handle();

        $providerClass = $this->getProviderClass();
        $this->info("Add provider in config/app.php: ");
        $this->info("'providers' => [
            ...
            {$providerClass}
        ];");

    }

    private function getProviderClass() {
        $module = $this->option("module");
        $name = $this->argument("name");

        $namespaceModule = $this->getNamespaceForModule($module);

        return "{$namespaceModule}\Providers\\{$name}::class";
    }
}
