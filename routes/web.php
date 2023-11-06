<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\SacredTrustEntry;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    // return view('welcome');
    return view('staff');
});
// Route::get('/st_entry', SacredTrustEntry::class);

// Route::redirect('/admin/login', '/login')->name('filament.auth.login');

Route::redirect('/login', '/admin/login')->name('login');
// Route::redirect('/register', '/admin/register')->name('register');

Route::redirect('/register', '/admin/register')->name('register');