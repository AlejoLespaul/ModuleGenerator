<?php
namespace ModuleGenerator\Test;

use Illuminate\Support\Facades\File;
use Tests\TestCase;

class MakeMigrationForModuleTest extends TestCase
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
    public function can_create_a_migration()
    {

        $this->artisan("module:migration", [
            "name" => "test_migration",
            "--module" => "Test"
        ])->expectsOutput("test_migration migration created");

        $files = File::files($this->workdir . "/modules/Test/database/migrations");

        $this->assertEquals(1, count($files));
    }
}
