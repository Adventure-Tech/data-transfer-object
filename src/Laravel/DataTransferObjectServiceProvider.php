<?php

namespace AdventureTech\DataTransferObject\Laravel;

use AdventureTech\DataTransferObject\Console\Commands\DtoMakeCommand;
use Illuminate\Support\ServiceProvider;

class DataTransferObjectServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                DtoMakeCommand::class,
            ]);
        }
    }
}