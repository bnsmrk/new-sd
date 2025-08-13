<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuestionController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/', [QuestionController::class, 'create'])->name('aiken.create');
Route::post('/questions', [QuestionController::class, 'store'])->name('questions.store');

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
