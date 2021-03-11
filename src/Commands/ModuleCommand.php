<?php

namespace ModuleGenerator\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Laminas\Config\Reader\Json as JsonReader;
use Laminas\Config\Writer\Json as JsonWritter;

class ModuleCommand extends Command
{
    /**
     * @var Filesystem
     */
    protected $files;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Module';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
        $this->workdir = config("module_generator.workdir");
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument("name");
        $this->makeDirectory($name);
        $this->makeProvider($name);
        $this->registerInComposer($name);
        $this->info("Module {$name} Created");

        return 0;
    }


    private function registerInComposer($module) {
        $pathFile = $this->workdir . "composer.json";
        $reader = new JsonReader();
        $content = $reader->fromFile($pathFile);

        $content = $this->addModuleToContent($module, $content);

        $writer = new JsonWritter();
        $writer->toFile($pathFile, $content);
    }

    private function addModuleToContent($module, $content) {
        $content["autoload"]["psr-4"][$this->getNameSpace($module)] = $this->getPathForModule($module) . "/";
        return $content;
    }

    private function getNameSpace($module){

        return str_replace("/", "\\", $module) . "\\";
    }

    private function makeDirectory($module) {
        $path = $this->getPathForModule($module);
        if (! $this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0777, true, true);
        }
    }

    private function getPathForModule($module){
        return $this->workdir . "modules/" . $module;
    }

    private function makeProvider($module){
        $nameProvider = $this->getNameProvider($module);

        $this->call('module:provider', [
            'name' => $nameProvider,
            '--module' => $module,
        ]);
    }

    private function getNameProvider($module) {
        return (strrpos($module, "/") ? substr($module, strrpos($module, "/") +1 ) : $module) . "Provider";
    }

}
