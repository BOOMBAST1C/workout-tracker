<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\WorkoutController;
use App\Http\Controllers\AdminController;

// --- АВТОРИЗАЦИЯ ---
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- ОСНОВНЫЕ РАЗДЕЛЫ ---
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog');

// --- МОИ ТРЕНИРОВКИ (Только для авторизованных) ---
Route::middleware('auth')->group(function () {
    Route::get('/workouts', [WorkoutController::class, 'index'])->name('workouts.index');
    Route::get('/workouts/create', [WorkoutController::class, 'create'])->name('workouts.create');
    Route::post('/workouts', [WorkoutController::class, 'store'])->name('workouts.store');
    Route::delete('/workouts/{id}', [WorkoutController::class, 'destroy'])->name('workouts.destroy');
    Route::get('/workouts/{id}', [WorkoutController::class, 'show'])->name('workouts.show');
    });

// --- АДМИН-ПАНЕЛЬ ---
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin');
    
    // Управление пользователями
    Route::post('/admin/user/{userId}/role', [AdminController::class, 'assignRole'])->name('admin.assignRole');
    
    // Управление группами мышц
    Route::post('/admin/muscle-groups', [AdminController::class, 'storeMuscleGroup'])->name('admin.muscleGroups.store');
    Route::put('/admin/muscle-groups/{id}', [AdminController::class, 'updateMuscleGroup'])->name('admin.muscleGroups.update');
    Route::delete('/admin/muscle-groups/{id}', [AdminController::class, 'destroyMuscleGroup'])->name('admin.muscleGroups.destroy');
    
    // Управление упражнениями
    Route::post('/admin/exercises', [AdminController::class, 'storeExercise'])->name('admin.exercises.store');
    Route::put('/admin/exercises/{id}', [AdminController::class, 'updateExercise'])->name('admin.exercises.update');
    Route::delete('/admin/exercises/{id}', [AdminController::class, 'destroyExercise'])->name('admin.exercises.destroy');
});