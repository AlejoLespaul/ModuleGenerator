<?php

namespace ModuleGenerator\Test;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use ModuleGenerator\Commands\Constants;
use Tests\TestCase;

class MakeControllerForModuleTest extends TestCase
{
    private $workdir = __DIR__ . "/files";

    /**
     * @tearDown
     */
    public function tearDown() : void {
        $utils = new TestUtils();
        if(file_exists($this->workdir . "/modules"))
            $utils->deleteDir($this->workdir . "/modules");
    }


    public function testCanCreateControllerForModule()
    {
        $name = "TestController";
        $module = "Test";
        $this->artisan("module:controller", [
            "name" => $name,
            "--module" => $module
        ])->expectsOutput("TestController created");

        $this->assertFileExists($this->workdir . "/modules/Test/Controller/TestController.php");
    }

    public function testCanCreateAControllerInSubFolder()
    {
        $name = "TestController";
        $module = "Test/Auth";
        $this->artisan("module:controller", [
            "name" => $name,
            "--module" => $module
        ])->expectsOutput("TestController created");

        $this->assertFileExists($this->workdir . "/modules/Test/Auth/Controller/TestController.php");
    }

    /**
     * A basic feature test example.
     * @test
     * @return void
     *
     */
    public function module_option_is_required()
    {
        $this->artisan("module:controller",[
            "name" => "TestController",
        ])->expectsOutput(Constants::MODULE_OPTION_REQUIRED);

        $this->assertFileDoesNotExist($this->workdir . "/modules/Controller/TestController.php");
    }


}
