<?php

namespace ModuleGenerator\Test;

use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use function PHPUnit\Framework\assertTrue;
use function PHPUnit\Framework\directoryExists;

class CreateModuleTest extends TestCase
{

    /**
     * @tearDown
     */
    public function tearDown() : void {
       $this->deleteDir($this->workdir . "/modules");
    }

    private $workdir = __DIR__ . "/files";
    /**
     * @test
     */
    public function it_can_create_a_module()
    {
        $this->artisan("module:make", [
            "name" => "Test"
        ])->expectsOutput("Module Test Created")
        ->expectsOutput("Add provider in config/app.php: ")
        ->expectsOutput("'providers' => [
            ...
            Test\Providers\TestProvider::class,
        ];");

        $this->assertFileExists($this->workdir . "/modules/Test");
    }

    /**
     * @test
     */
    public function it_create_a_provider()
    {
        $this->artisan("module:make", [
            "name" => "Test"
        ]);

        $this->assertFileExists($this->workdir . "/modules/Test/Providers/TestProvider.php");
    }

    /**
     * @test
     */
    public function it_can_register_a_new_module_in_composer_json()
    {
        $this->artisan("module:make", [
            "name" => "Test"
        ]);

        $this->assertStringContainsString("Test\\", file_get_contents($this->workdir."/composer.json"));
    }

    /**
     * @test
     */
    public function it_can_create_a_routes_file(){
        $this->artisan("module:make", [
            "name" => "Test"
        ]);

        $this->assertFileExists($this->workdir . "/modules/Test/routes.php");
        $this->assertStringContainsString("routes.php", file_get_contents($this->workdir . "/modules/Test/Providers/TestProvider.php"));
        $this->assertStringContainsString("Hello test", file_get_contents($this->workdir . "/modules/Test/routes.php"));

    }

    public function deleteDir($dirPath) {
        if (! is_dir($dirPath)) {
            throw new Exception("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }
}
