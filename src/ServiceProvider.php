<?php


namespace Goldcarrot;

use Goldcarrot\Console\DomainMakeCommand;
use Goldcarrot\Console\EnumsMakeCommand;
use Goldcarrot\Console\PresenterMakeCommand;
use Goldcarrot\Console\RepositoryMakeCommand;
use Goldcarrot\Console\ServiceMakeCommand;
use Goldcarrot\Console\ValidatorMakeCommand;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        $this->commands([
            DomainMakeCommand::class,
            EnumsMakeCommand::class,
            PresenterMakeCommand::class,
            RepositoryMakeCommand::class,
            ServiceMakeCommand::class,
            ValidatorMakeCommand::class,
        ]);
    }
}