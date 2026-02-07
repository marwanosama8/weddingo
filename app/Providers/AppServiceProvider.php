<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
use Illuminate\Foundation\Vite;

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
        Filament::serving(function () {
            // Using Vite
            Filament::registerTheme(
                app(Vite::class)('resources/css/filament.css'),
            );
            Filament::registerNavigationGroups([
                NavigationGroup::make()
                    ->label('Vendors')
                    ->icon('heroicon-s-identification'),
                NavigationGroup::make()
                    ->label('Settings')
                    ->icon('heroicon-s-cog'),
                NavigationGroup::make()
                    ->label('App')
                    ->icon('heroicon-s-server'),
            ]);
        });
    }
}
