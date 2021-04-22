<?php

namespace ModuleGenerator\Test;

use Tests\TestCase;

class MakeCommandForModuleTest extends TestCase
{
    private $workdir = __DIR__ . "/files";

    /**
     * @tearDown
     */
    public function tearDown() : void {
        $utils = new TestUtils();
        if(file_exists($this->workdir . "/modules")){
            //$utils->deleteDir($this->workdir . "/modules");
        }
    }

        /**
     * A basic feature test example.
     * @test
     * @return void
     */
    public function can_create_a_command()
    {
        $this->artisan("module:command", [
            "name" => "TestCommand",
            "--module" => "Test"
        ])->expectsOutput("TestCommand command created");

        $this->assertFileExists($this->workdir . "/modules/Test/Console/Commands/TestCommand.php");
    }
}
