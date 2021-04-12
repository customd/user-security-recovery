<?php

namespace CustomD\UserSecurityRecovery;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    protected const CONFIG_PATH = __DIR__ . '/../config/user-security-recovery.php';

    protected const DB_PATH = __DIR__ . '/../database/migrations';

    public function boot()
    {
        $this->publishes([
            self::CONFIG_PATH => config_path('user-security-recovery.php'),
        ], 'config');

        $this->loadMigrationsFrom(self::DB_PATH);
    }

    public function register()
    {
        $this->mergeConfigFrom(
            self::CONFIG_PATH,
            'user-security-recovery'
        );
    }
}
