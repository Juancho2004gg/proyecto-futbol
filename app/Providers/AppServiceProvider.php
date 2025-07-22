<?php

namespace App\Providers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();
        if ($this->app->environment('production')) {
            \URL::forceScheme('https');
        }
        Log::info('Cloudinary config', [
            'cloud_name_raw' => env('CLOUDINARY_CLOUD_NAME'),
            'cloud_name_trimmed' => trim(env('CLOUDINARY_CLOUD_NAME'), '"'),
            'api_key_raw' => env('CLOUDINARY_API_KEY'),
            'api_key_trimmed' => trim(env('CLOUDINARY_API_KEY'), '"'),
            'api_secret_raw' => env('CLOUDINARY_API_SECRET'),
            'api_secret_trimmed' => trim(env('CLOUDINARY_API_SECRET'), '"'),
        ]);
    }
}
