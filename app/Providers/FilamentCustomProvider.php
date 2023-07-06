<?php

namespace App\Providers;

use Filament\Facades\Filament;
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
        Filament::serving(function () {
            if (Auth::user()->can('access tools')) {
                Filament::registerUserMenuItems([
                    UserMenuItem::make()
                    ->label('Tools')
                    ->url(route('filament.pages.tools'))
                    ->icon('heroicon-s-cog'),
                    // ...
                ]);
            }
        });
    }
}



