<?php

namespace ModuleGenerator\Test;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MakeFactoryForModuleTest extends TestCase
{
    private $workdir = __DIR__ . "/files";

    /**
     * @tearDown
     */
    public function tearDown() : void {
        $utils = new TestUtils();
        if(file_exists($this->workdir . "/modules")){
            $utils->deleteDir($this->workdir . "/modules");
        }
    }

     /**
     * A basic feature test example.
     * @test
     * @return void
     */
    public function can_create_a_factory()
    {
        $this->artisan("module:factory", [
            "name" => "TestFactory",
            "--module" => "Test"
        ])->expectsOutput("TestFactory factory created");

        $this->assertFileExists($this->workdir . "/modules/Test/database/factories/TestFactory.php");
    }
}
