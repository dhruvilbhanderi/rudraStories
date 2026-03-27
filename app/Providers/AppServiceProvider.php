<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use App\Models\ProfileModel;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        if (config('app.env') !== 'local') {
            URL::forceScheme('https');
        }

        View::composer('components.navbar', function ($view) {
            $profileImage = null;

            if (session()->has(['usnm', 'loginstat'])) {
                try {
                    $profileImage = ProfileModel::where('UserName', session('usnm'))->value('images');
                } catch (\Throwable $e) {
                    $profileImage = null;
                }
            }

            $view->with('data', $profileImage);
        });
    }
}
