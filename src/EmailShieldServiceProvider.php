<?php

namespace Unctom\EmailShield;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Unctom\EmailShield\Commands\EmailShieldCommand;

class EmailShieldServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-email-shield')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel_email_shield_table')
            ->hasCommand(EmailShieldCommand::class);
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(EmailShield::class, function () {
            return new EmailShield;
        });
    }
}
