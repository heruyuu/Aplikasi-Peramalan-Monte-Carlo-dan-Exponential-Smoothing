<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class CustomServiceProvider extends ServiceProvider {
    public function register() {
        require_once app_path() . '/Helpers/Custom.php';
    }
    public function boot() {
        //
    }
}
