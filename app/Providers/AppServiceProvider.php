<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Resources\RegistrationResource;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        RegistrationResource::withoutWrapping();
    }
}
