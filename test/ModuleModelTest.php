<?php

namespace ModuleGenerator\Test;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ModuleModelTest extends TestCase
{
    private $workdir = __DIR__ . "/files";

    /**
     * @tearDown
     */
    public function tearDown() : void {
        $utils = new TestUtils();
        $utils->deleteDir($this->workdir . "/modules");
    }

    /**
     * A basic feature test example.
     * @test
     * @return void
     */
    public function can_create_a_model()
    {
        $this->artisan("module:model", [
            "name" => "TestModel",
            "--module" => "Test"
        ])->expectsOutput("TestModel model created");

        $this->assertFileExists($this->workdir . "/modules/Test/Model/TestModel.php");
    }

    /**
     * A basic feature test example.
     * @test
     * @return void
     */
    public function can_create_a_model_in_sub_folder()
    {
        $this->artisan("module:model", [
            "name" => "TestModel",
            "--module" => "Test/Auth"
        ])->expectsOutput("TestModel model created");

        $this->assertFileExists($this->workdir . "/modules/Test/Auth/Model/TestModel.php");
    }
}
