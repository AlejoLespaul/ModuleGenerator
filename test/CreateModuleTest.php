<?php

namespace ModuleGenerator\Test;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use function PHPUnit\Framework\directoryExists;

class CreateModuleTest extends TestCase
{

    /**
     * @tearDown
     */
    public function tearDown() : void {
        shell_exec("rm -rf files/modules/*");
    }

    private $workdir = __DIR__ . "/files";
    /**
     * @test
     */
    public function it_can_create_a_module()
    {
        $this->artisan("make:module", [
            "name" => "Test"
        ])->expectsOutput("Module Test Created")
        ->expectsOutput("Add provider in config/app.php: ")
        ->expectsOutput("'providers' => [
            ...
            Test\Providers\TestProvider::class,
        ];");

        $this->assertFileExists($this->workdir . "/modules/Test");
    }

    /**
     * @test
     */
    public function it_create_a_provider()
    {
        $this->artisan("make:module", [
            "name" => "Test"
        ]);

        $this->assertFileExists($this->workdir . "/modules/Test/Providers/TestProvider.php");
    }

    /**
     * @test
     */
    public function it_can_register_a_new_module_in_composer_json()
    {
        $this->artisan("make:module", [
            "name" => "Test"
        ]);

        $this->assertStringContainsString("Test\\", file_get_contents($this->workdir."/composer.json"));
    }

}
