<?php
namespace ModuleGenerator\Test;
use Tests\TestCase;

require_once("TestUtils.php");

class CreateModuleTest extends TestCase
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
     * @test
     */
    public function it_can_create_a_module()
    {
        $this->artisan("module:make", [
            "name" => "Test"
        ])->expectsOutput("Module Test Created");

        $this->assertFileExists($this->workdir . "/modules/Test");
    }

    /**
     * @test
     */
    public function it_create_the_provider_for_module()
    {
        $this->artisan("module:make", [
            "name" => "Test"
        ])
        ->expectsOutput("'providers' => [
            ...
            Test\\Providers\\TestProvider::class
        ];");

        $this->assertFileExists($this->workdir . "/modules/Test/Providers/TestProvider.php");
    }

    /**
     * @test
     */
    public function it_can_register_the_new_module_in_composer_json()
    {
        $this->artisan("module:make", [
            "name" => "Test"
        ]);

        $this->assertStringContainsString("Test\\", file_get_contents($this->workdir."/composer.json"));
    }

    /**
     * @test
     */
    public function it_can_create_a_module_with_sub_folders()
    {
        $this->artisan("module:make", [
            "name" => "User/Test"
        ]);

        $this->assertStringContainsString("User\\\\Test\\\\", file_get_contents($this->workdir."/composer.json"));
        $this->assertFileExists($this->workdir . "/modules/User/Test/Providers/TestProvider.php");
        $this->assertStringContainsString("namespace User\Test\Providers", file_get_contents($this->workdir . "/modules/User/Test/Providers/TestProvider.php"));
    }

    /**
     * @test
     */
    public function it_can_create_a_module_with_many_sub_folders()
    {
        $this->artisan("module:make", [
            "name" => "Group/User/Test"
        ]);

        $this->assertStringContainsString("Group\\\\User\\\\Test\\\\", file_get_contents($this->workdir."/composer.json"));
        $this->assertFileExists($this->workdir . "/modules/Group/User/Test/Providers/TestProvider.php");
        $this->assertStringContainsString("namespace Group\User\Test\Providers", file_get_contents($this->workdir . "/modules/Group/User/Test/Providers/TestProvider.php"));
    }

}
