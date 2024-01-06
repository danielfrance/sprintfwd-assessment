<?php

use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TeamController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('teams', TeamController::class);
Route::get('teams/{id}/members', [TeamController::class, 'members']);

Route::apiResource('members', MemberController::class);
Route::put('members/{id}/team', [MemberController::class, 'updateTeam']);

Route::apiResource('projects', ProjectController::class);
Route::put('projects/{id}/members/add', [ProjectController::class, 'addMembers']);
Route::put('projects/{id}/members/remove', [ProjectController::class, 'removeMembers']);