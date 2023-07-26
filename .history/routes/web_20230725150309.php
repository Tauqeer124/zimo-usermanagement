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
// Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');

Route::get('/register', [AuthController::class, 'register']);
Route::post('/register', [AuthController::class, 'registeruser'])->name('register');
Route::get('/login', [AuthController::class, 'login']);
Route::post('/login', [AuthController::class, 'loginuser'])->name('login');
//dashboard route
Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
//user route
Route::get('/user/show/{id}', [UserController::class, 'show'])->name('user.show');
Route::get('/user/add', [UserController::class, 'create'])->name('user.create');
Route::post('/user/add', [UserController::class, 'store'])->name('adduser');
Route::get('/useredit/{id}/', [UserController::class, 'edit'])->name('users.edit');
Route::put('/user/{id}',[])
Route::get('/users', [UserController::class, 'index'])->name('user.index');


Route::get('/userdel/{id}', [UserController::class, 'destroy'])->name('user.delete');

