<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;

// Main Page
Route::get('/', [TodoController::class, 'index'])->name('todos.index');

// Create Page
Route::get('/create', [TodoController::class, 'create'])->name('todos.create');

// Save New Todo
Route::post('/store', [TodoController::class, 'store'])->name('todos.store');

// Edit Page (The one causing the error)
Route::get('/todos/{todo}/edit', [TodoController::class, 'edit'])->name('todos.edit');

// Update Todo
Route::put('/todos/{todo}', [TodoController::class, 'update'])->name('todos.update');

// Delete Todo
Route::delete('/todos/{todo}', [TodoController::class, 'destroy'])->name('todos.destroy');