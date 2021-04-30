<?php


namespace GoldcarrotLaravel\Base;

use GoldcarrotLaravel\Base\Console\DomainMakeCommand;
use GoldcarrotLaravel\Base\Console\EnumsMakeCommand;
use GoldcarrotLaravel\Base\Console\PresenterMakeCommand;
use GoldcarrotLaravel\Base\Console\RepositoryMakeCommand;
use GoldcarrotLaravel\Base\Console\ServiceMakeCommand;
use GoldcarrotLaravel\Base\Console\ValidatorMakeCommand;
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