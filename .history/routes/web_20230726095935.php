<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

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

// Dashboard route
// Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');

    // User routes
    Route::get('/user/show/{id}', [UserController::class, 'show'])->name('user.show');
    Route::get('/user/add', [UserController::class, 'create'])->name('user.create');
    Route::post('/user/add', [UserController::class, 'store'])->name('adduser');
    Route::get('/useredit/{id}', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/userupdate/{id}', [UserController::class, 'update'])->name('user.update');
    Route::get('/users', [UserController::class, 'index'])->name('user.index');
    Route::get('/userdel/{id}', [UserController::class, 'destroy'])->name('user.delete');
    //graph routes
    Route::get('/user/graph', [UserController::class ,'showgraph'])->name('user.graph');
    Route::get('/user/data/{country}', [UserController::class ,'showuserdata'])->name('user-data');
    //toggle status
    Route::post('/users/{user}/toggle-status', 'App\Http\Controllers\UserController@toggleUserStatus')->name('users.toggle-status');

// });

    




