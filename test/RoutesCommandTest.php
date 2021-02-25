<?php

namespace ModuleGenerator\Test;
use Tests\TestCase;

class RoutesCommandTest extends TestCase
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
    public function create_routes_for_module()
    {
        $this->artisan("module:routes",[
            "name" => "routes",
            "--module" => "Test"
        ])->expectsOutput("File routes.php created")
          ->expectsOutput("Add routes in boot module provider method, e.g.: ");

          $this->assertFileExists($this->workdir . "/modules/Test/routes.php");
    }
}
