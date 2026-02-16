<?php 
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

Route::middleware(['auth'])->group(function () {

    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');

});


Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

Route::post('/login', [AuthController::class, 'login']);

Route::get('/dashboard', [AuthController::class, 'dashboard'])
    ->middleware('auth');

Route::get('/logout', [AuthController::class, 'logout']);

Route::get('/session-test', function () {
    session(['test' => 'working']);
    return session('test');
});



