<?php


namespace ModuleGenerator\Commands;

trait AssertModuleOptions
{
    public function assertModuleOptionExists(): void
    {
        if (!$this->option("module")) {
            throw new \Exception(Constants::MODULE_OPTION_REQUIRED);
        }
    }
}
