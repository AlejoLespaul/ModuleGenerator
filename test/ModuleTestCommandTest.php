<?php

namespace ModuleGenerator\Test;

use Tests\TestCase;

class ModuleTestCommandTest extends TestCase
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
    public function create_test_for_a_module()
    {
        $this->artisan("module:test", [
            "name" => "FooTest",
            "--module" => "Test"
        ])
        ->expectsOutput("FooTest class created for module");

        $this->assertFileExists($this->workdir . "/modules/Test/tests/Feature/FooTest.php");
    }

    /**
     * A basic feature test example.
     * @test
     * @return void
     */
    public function create_a_unit_test_for_module()
    {
        $this->artisan("module:test", [
            "name" => "BarTest",
            "--module" => "Test",
            "--unit" => true
        ])
        ->expectsOutput("BarTest class created for module");

        $this->assertFileExists($this->workdir . "/modules/Test/tests/Unit/BarTest.php");
    }

    /**
     * A basic feature test example.
     * @test
     * @return void
     */
    public function register_test_module_in_composer()
    {
        $this->artisan("module:test", [
            "name" => "BarTest",
            "--module" => "Test",
            "--unit" => true
        ])
        ->expectsOutput("BarTest class created for module");

        $this->assertStringContainsString("Test\\\\Tests\\\\", file_get_contents($this->workdir."/composer.json"));
    }

}
