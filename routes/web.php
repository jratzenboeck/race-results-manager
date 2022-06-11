<?php

use App\Http\Controllers\TriathlonRaceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');


Route::get('/races/create', [\App\Http\Controllers\RaceController::class, 'create'])
    ->middleware(['auth'])
    ->name('races.create');
Route::post('/triathlon-races', [TriathlonRaceController::class, 'store']);

require __DIR__.'/auth.php';
