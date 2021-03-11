<?php

namespace ModuleGenerator\Test;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ModuleControllerTest extends TestCase
{
    private $workdir = __DIR__ . "/files";

    /**
     * @tearDown
     */
    public function tearDown() : void {
        $utils = new TestUtils();
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


}
