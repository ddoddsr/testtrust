<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Navigation\MenuItem;
use Illuminate\Support\Facades\Auth;
use Filament\Navigation\UserMenuItem;
use Illuminate\Support\ServiceProvider;

class FilamentCustomProvider extends ServiceProvider
{
    
    /**
     * Register services.
     */
    public function register(): void
    {
        // 
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // TODO get url 
        // Filament::serving(function () {
        //     if (Auth::user() && Auth::user()->can('access tools')) {
        //         Filament::registerUserMenuItems([
        //             MenuItem::make()
        //             ->label('Tools')
        //             //TODO url not correct
        //             // ->url(route('filament.pages.tools'))
        //             ->icon('heroicon-s-cog'),
        //             // ...
        //         ]);
        //     }
        // });
    }
}



