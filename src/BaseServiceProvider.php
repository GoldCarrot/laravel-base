<?php


namespace GoldcarrotLaravel\Base;

use GoldcarrotLaravel\Base\Console\DomainMakeCommand;
use GoldcarrotLaravel\Base\Console\EnumMakeCommand;
use GoldcarrotLaravel\Base\Console\PresenterMakeCommand;
use GoldcarrotLaravel\Base\Console\RepositoryMakeCommand;
use GoldcarrotLaravel\Base\Console\ServiceMakeCommand;
use GoldcarrotLaravel\Base\Console\ValidatorMakeCommand;
use Illuminate\Support\ServiceProvider;

class BaseServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands([
            DomainMakeCommand::class,
            EnumMakeCommand::class,
            PresenterMakeCommand::class,
            RepositoryMakeCommand::class,
            ServiceMakeCommand::class,
            ValidatorMakeCommand::class,
        ]);
    }
}