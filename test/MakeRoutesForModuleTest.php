<?php

namespace ModuleGenerator\Test;
use ModuleGenerator\Commands\Constants;
use ModuleGenerator\Commands\MakeRoutesForModule;
use Tests\TestCase;

class MakeRoutesForModuleTest extends TestCase
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
    public function create_routes_for_module()
    {
        $this->artisan("module:routes",[
            "name" => "routes",
            "--module" => "Test"
        ])->expectsOutput("File routes.php created")
          ->expectsOutput("Add routes in boot module provider method, e.g.: ");

          $this->assertFileExists($this->workdir . "/modules/Test/routes.php");
    }

    /**
     * A basic feature test example.
     * @test
     * @return void
     *
     */
    public function module_option_is_required()
    {
        $response = $this->artisan("module:routes",[
            "name" => "routes",
        ])->expectsOutput(Constants::MODULE_OPTION_REQUIRED);

        $this->assertFileDoesNotExist($this->workdir . "/modules/routes.php");
    }
}
