<?php

namespace ModuleGenerator\Test;

use ModuleGenerator\Commands\Constants;
use Tests\TestCase;

class MakeProviderForModuleTest extends TestCase
{
    private $workdir = __DIR__ . "/files";

    /**
     * @tearDown
     */
    public function tearDown(): void
    {
        $utils = new TestUtils();
        if (file_exists($this->workdir . "/modules"))
            $utils->deleteDir($this->workdir . "/modules");
    }

    /**
     * A basic feature test example.
     * @test
     * @return void
     */
    public function create_provider_for_module()
    {
        $this->artisan('module:provider', [
            'name' => "TestProvider",
            '--module' => "Test",
        ])->expectsOutput("Provider Created");

        $this->assertFileExists($this->workdir . "/modules/Test/Provider/TestProvider.php");
    }

    /**
     * A basic feature test example.
     * @test
     * @return void
     *
     */
    public function module_option_is_required()
    {
        $this->artisan('module:provider', [
            'name' => "TestProvider",
        ])->expectsOutput(Constants::MODULE_OPTION_REQUIRED);

        $this->assertFileDoesNotExist($this->workdir . "/modules/Provider/TestProvider.php");
    }
}
