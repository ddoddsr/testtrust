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
Route::get('/st_entry', SacredTrustEntry::class);

Route::middleware([
    'auth:sanctum',
    config('filament-companies.auth_session'),
    'verified'
])->group(function () {

});
