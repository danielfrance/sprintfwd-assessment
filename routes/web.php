<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Web\MemberController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\ProjectController;
use App\Http\Controllers\Web\TeamController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
    if (Auth::check()) {
        return redirect('/dashboard');
    }
    return redirect("/login");
});


Route::get('/api-calls', [DashboardController::class, 'getApiCalls'])->name('api-calls');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/dashboard-data', [DashboardController::class, 'getDashboardData'])->name('dashboard-data');

    Route::resource('teams', TeamController::class);
    Route::resource('members', MemberController::class);
    Route::resource('projects', ProjectController::class);
    
});

require __DIR__ . '/auth.php';
