<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use App\Models\Petugas;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (!Session::has('id_petugas')) {
            $token = Cookie::get('remember_token');

            if ($token) {
                $petugas = Petugas::where('remember_token', $token)->first();

                if ($petugas) {
                    // Set session agar tetap login
                    Session::put('login', true);
                    Session::put('id_petugas', $petugas->id_petugas);
                }
            }
        }
    }
}
