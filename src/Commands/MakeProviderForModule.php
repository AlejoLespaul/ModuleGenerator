<?php

namespace ModuleGenerator\Commands;

use Illuminate\Foundation\Console\ProviderMakeCommand;
use Laminas\Code\Generator\FileGenerator;
use Laminas\Code\Generator\ValueGenerator;
use Symfony\Component\Console\Input\InputOption;

class MakeProviderForModule extends ProviderMakeCommand
{

    use AssertModuleOptions;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'module:provider';

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        $module = $this->option("module");
        return $module . '\Provider';
    }

    protected function getPath($name)
    {
        $module = $this->option("module");
        $path = config("module_generator.workdir") . "modules/" . $this->getModuleFolder($module) . "/Provider/" . $name . ".php";

        return $path;
    }

    private function getModuleFolder($module)
    {
        return $module;
    }

    /**
     * Parse the class name and format according to the root namespace.
     *
     * @param string $name
     * @return string
     */
    protected function qualifyClass($name)
    {
        return $name;
    }

    protected function getNamespace($name)
    {
        $module = $this->option("module");
        return $this->getNamespaceForModule($module) . "\\Provider";
    }

    private function getNamespaceForModule($module)
    {
        return str_replace("/", "\\", $module);
    }

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

            $providerClass = $this->getProviderClass();
            $this->info("Provider Created");
            $this->comment("Add provider in config/app.php: ");
            $this->comment("'providers' => [
            ...
            {$providerClass}
        ];");
        } catch (\Exception $e) {
            $this->comment($e->getMessage());
        }

    }

    private function getProviderClass()
    {
        $module = $this->option("module");
        $name = $this->argument("name");

        $namespaceModule = $this->getNamespaceForModule($module);

        return "{$namespaceModule}\Provider\\{$name}::class";
    }

    protected function getOptions()
    {
        return [
            ['--module', 'M', InputOption::VALUE_REQUIRED, 'Module to create the component.'],
        ];
    }
}
