<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
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

        // PENTING: Memberi tahu Laravel untuk menggunakan template Bootstrap 5
        // Laravel 10/11/12 secara default menggunakan Tailwind, jadi harus diganti.
        Paginator::useBootstrapFive(); // <<< Solusi Utama

    }
}
