<?php
namespace ModuleGenerator\Test;
use Tests\TestCase;

require_once("TestUtils.php");

class AddModuleTest extends TestCase
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
     * @test
     */
    public function it_can_create_a_module()
    {
        $this->artisan("module:add", [
            "name" => "Test"
        ])->expectsOutput("Module Test Created");

        $this->assertFileExists($this->workdir . "/modules/Test");
    }

    /**
     * @test
     */
    public function it_create_the_provider_for_module()
    {
        $this->artisan("module:add", [
            "name" => "Test"
        ])
        ->expectsOutput("'providers' => [
            ...
            Test\\Provider\\TestProvider::class
        ];");

        $this->assertFileExists($this->workdir . "/modules/Test/Provider/TestProvider.php");
    }

    /**
     * @test
     */
    public function it_can_register_the_new_module_in_composer_json()
    {
        $this->artisan("module:add", [
            "name" => "Test"
        ]);

        $this->assertStringContainsString("Test\\", file_get_contents($this->workdir."/composer.json"));
    }

    /**
     * @test
     */
    public function it_can_create_a_module_with_sub_folders()
    {
        $this->artisan("module:add", [
            "name" => "User/Test"
        ]);

        $this->assertStringContainsString("User\\\\Test\\\\", file_get_contents($this->workdir."/composer.json"));
        $this->assertFileExists($this->workdir . "/modules/User/Test/Provider/TestProvider.php");
        $this->assertStringContainsString("namespace User\Test\Provider", file_get_contents($this->workdir . "/modules/User/Test/Provider/TestProvider.php"));
    }

    /**
     * @test
     */
    public function it_can_create_a_module_with_many_sub_folders()
    {
        $this->artisan("module:add", [
            "name" => "Group/User/Test"
        ]);

        $this->assertStringContainsString("Group\\\\User\\\\Test\\\\", file_get_contents($this->workdir."/composer.json"));
        $this->assertFileExists($this->workdir . "/modules/Group/User/Test/Provider/TestProvider.php");
        $this->assertStringContainsString("namespace Group\User\Test\Provider", file_get_contents($this->workdir . "/modules/Group/User/Test/Provider/TestProvider.php"));
    }

}
