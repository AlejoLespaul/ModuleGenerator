<?php

namespace ModuleGenerator\Commands;
use Illuminate\Foundation\Console\TestMakeCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;
use Laminas\Config\Reader\Json as JsonReader;
use Laminas\Config\Writer\Json as JsonWritter;
class MakeTestForModule extends TestMakeCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'module:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make Test for module';

    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace()
    {
        $module = $this->option("module");
        return str_replace("/", "\\", $module) . "\Tests";
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
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);
        return $this->getPathForModule($module).str_replace('\\', '/', $name).'.php';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array_merge( parent::getOptions(), [
            ['--module', 'M', InputOption::VALUE_REQUIRED, 'Create a unit test.'],
        ]);
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        parent::handle();
        $module = $this->option("module");
        $this->registerInComposer($module);
        $name = $this->argument("name");
        $this->info("{$name} class created for module");
        return 0;
    }

    private function registerInComposer($module) {
        $pathFile = config("module_generator.workdir") . "composer.json";
        $reader = new JsonReader();
        $content = $reader->fromFile($pathFile);

        $content = $this->addModuleToContent($module, $content);

        $writer = new JsonWritter();
        $writer->toFile($pathFile, $content);
    }

    private function getPathForModule($module) {
        return config("module_generator.workdir"). "modules/{$module}/"."tests";
    }

    private function addModuleToContent($module, $content) {
        $content["autoload-dev"]["psr-4"][$this->rootNamespace(). "\\"] = $this->getPathForModule($module) . "/";
        return $content;
    }
}
