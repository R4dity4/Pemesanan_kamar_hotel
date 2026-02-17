<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;

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
        // Auto-generate breadcrumbs based on route name
        View::composer('partials.breadcrumb', function ($view) {
            $routeName = Route::currentRouteName();
            $breadcrumbs = [];

            $map = [
                'kamar'           => [['label' => 'Kamar & Suite', 'url' => '/kamar']],
                'kamar.show'      => [['label' => 'Kamar & Suite', 'url' => '/kamar'], ['label' => 'Detail Kamar', 'url' => '#']],
                'reservasi'       => [['label' => 'Reservasi', 'url' => '/reservasi']],
                'reservasi.cek'   => [['label' => 'Reservasi', 'url' => '/reservasi'], ['label' => 'Cek Status', 'url' => '#']],
                'reservasi.invoice' => [['label' => 'Reservasi', 'url' => '/reservasi'], ['label' => 'Invoice', 'url' => '#']],
                'aktivitas'       => [['label' => 'Aktivitas', 'url' => '/aktivitas']],
                'fasilitas'       => [['label' => 'Fasilitas', 'url' => '/fasilitas']],
                'kontak'          => [['label' => 'Kontak Kami', 'url' => '/kontak']],
                'resto'           => [['label' => 'Resto & Kafe', 'url' => '/resto']],
            ];

            if (isset($map[$routeName])) {
                $breadcrumbs = $map[$routeName];
            }

            $view->with('breadcrumbs', $breadcrumbs);
        });
    }
}
