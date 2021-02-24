<?php

namespace ModuleGenerator\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Laminas\Config\Reader\Json as JsonReader;
use Laminas\Config\Writer\Json as JsonWritter;

class ModuleCreateCommand extends Command
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
    protected $signature = 'make:module {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Module';

    protected $workdir;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
        $this->workdir = config("module_generator.workdir", __DIR__ . "/../../test/files/");
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
        $this->info("Module $name Created");
        $this->info("Add provider in config/app.php: ");

        $this->info("'providers' => [
            ...
            {$name}\Providers\\{$name}Provider::class,
        ];");

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
        $content["autoload"]["psr-4"][$module . "\\"] = $this->getPathForModule($module) . "/";
        return $content;
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
        $nameProvider = $module . "Provider";

        $this->call('module:provider', [
            'name' => $nameProvider,
            '--module' => $module,
        ]);
    }

}
