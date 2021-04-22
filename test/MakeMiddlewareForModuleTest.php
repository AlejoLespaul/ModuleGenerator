<?php

namespace ModuleGenerator\Test;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MakeMiddlewareForModuleTest extends TestCase
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
    public function can_create_a_middleware()
    {
        $this->artisan("module:middleware", [
            "name" => "TestMiddle",
            "--module" => "Test"
        ])->expectsOutput("TestMiddle middleware created");

        $this->assertFileExists($this->workdir . "/modules/Test/Http/Middleware/TestMiddle.php");
    }
}
