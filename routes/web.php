<?php

use App\Http\Controllers\AIController;
use App\Http\Controllers\ConceptController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('domains', DomainController::class);

    Route::get('/domains/{domain}/concepts', [ConceptController::class, 'index'])->name('domains.concepts.index');
    Route::get('/domains/{domain}/concepts/create', [ConceptController::class, 'create'])->name('domains.concepts.create');
    Route::post('/domains/{domain}/concepts', [ConceptController::class, 'store'])->name('domains.concepts.store');

    Route::get('/concepts/{concept}', [ConceptController::class, 'show'])->name('concepts.show');
    Route::get('/concepts/{concept}/edit', [ConceptController::class, 'edit'])->name('concepts.edit');
    Route::put('/concepts/{concept}', [ConceptController::class, 'update'])->name('concepts.update');
    Route::delete('/concepts/{concept}', [ConceptController::class, 'destroy'])->name('concepts.destroy');
    Route::post('/concepts/{concept}/toggle-status', [ConceptController::class, 'toggleStatus'])->name('concepts.toggle-status');

    Route::post('/concepts/{concept}/generate', [AIController::class, 'generate'])->name('concepts.generate');
    Route::delete('/generated-questions/{generatedQuestion}', [AIController::class, 'destroy'])->name('generated-questions.destroy');
});

require __DIR__.'/auth.php';
