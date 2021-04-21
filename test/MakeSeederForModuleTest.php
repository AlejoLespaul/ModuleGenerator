<?php

namespace ModuleGenerator\Test;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MakeSeederForModuleTest extends TestCase
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
    public function can_create_a_seeder()
    {

        $this->artisan("module:seeder", [
            "name" => "SeederTest",
            "--module" => "Test"
        ])->expectsOutput("SeederTest seeder created");

        $this->assertFileExists($this->workdir . "/modules/Test/database/seeders/SeederTest.php");
    }

}
