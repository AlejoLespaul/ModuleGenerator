<?php

namespace ModuleGenerator\Test;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\File;
use ModuleGenerator\Commands\Constants;
use Tests\TestCase;

class MakeModelForModuleTest extends TestCase
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

    /**
     * A basic feature test example.
     * @test
     * @return void
     *
     */
    public function module_option_is_required()
    {
        $this->artisan("module:model", [
            "name" => "TestModel",
        ])->expectsOutput(Constants::MODULE_OPTION_REQUIRED);

        $this->assertFileDoesNotExist($this->workdir . "/modules/Test/Auth/Model/TestModel.php");
    }

    /**
     * A basic feature test example.
     * @test
     * @return void
     */
    public function can_create_a_model_with_migration()
    {
        $this->artisan("module:model", [
            "name" => "TestModel",
            "--module" => "Test",
            "--migration" => "m"
        ])->expectsOutput("TestModel model created");

        $this->assertFileExists($this->workdir . "/modules/Test/Model/TestModel.php");
        $files = File::files($this->workdir . "/modules/Test/database/migrations");

        $this->assertEquals(1, count($files));
    }
}
