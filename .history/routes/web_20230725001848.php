<?php

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
    return view('welcome');
});
Route::get('/register', [AuthController::class, 'register']);
Route::post('/register', [AuthController::class, 'registeruser'])->name('register');
Route::get('/login', [AuthController::class, 'login']);
Route::post('/login', [AuthController::class, 'loginuser'])->name('login');
Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');

Route::get('/users', [UserController::class, 'index'])->name('user.index');
Route::get('/users/{id}', [UserController::class, 'show'])->name('user.show');
