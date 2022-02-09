<?php

namespace JumasCola\AdminChat;

use Illuminate\Support\ServiceProvider;

class AdminChatServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(AdminChat $extension)
    {
        if (! AdminChat::boot()) {
            return ;
        }

        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, 'adminchat');
        }

        if ($this->app->runningInConsole() && $assets = $extension->assets()) {
            $this->publishes(
                [$assets => public_path('vendor/laravel-admin-ext/adminchat')],
                'adminchat'
            );
        }

        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');

        $this->app->booted(function () {
            AdminChat::routes(__DIR__.'/../routes/web.php');
        });
    }
}
