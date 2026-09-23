<?php

use App\Http\Controllers\TaskController;
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

Route::get('/', [TaskController::class, 'index'])->name('tasks.index');
Route::resource('tasks', TaskController::class)->except(['index', 'show', 'create']);
Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.status');
