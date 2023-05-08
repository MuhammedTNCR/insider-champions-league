<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\FixtureController;

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
    return view('welcome');
});

Route::get('/teams', [TeamController::class, 'index'])->name('teams.index');
Route::post('/teams/generate-fixtures', [TeamController::class, 'generateFixtures'])->name('teams.generate_fixtures');
Route::get('/simulation', [FixtureController::class, 'simulation'])->name('fixtures.simulation');
Route::post('/play-next-week', [FixtureController::class, 'playNextWeek'])->name('fixtures.play_next_week');
