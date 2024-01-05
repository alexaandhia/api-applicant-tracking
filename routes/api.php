<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicantController;
use App\Models\Applicant;

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

//Route::resource('applicants', ApplicantController::class)->except(['create', 'edit', 'skill', 'save']);
Route::get('/token', [ApplicantController::class, 'token']);
Route::get('/search', [ApplicantController::class, 'search'])->name('search');
Route::get('/applicants', [ApplicantController::class, 'index'])->name('applicants');
Route::post('/add', [ApplicantController::class, 'store'])->name('add');
Route::patch('/update/{id}', [ApplicantController::class, 'update'])->name('update');
Route::get('/skill', [ApplicantController::class, 'addSkill'])->name('skill');
Route::post('/skillAdd', [ApplicantController::class, 'save'])->name('skillAdd');
Route::delete('/delete/{id}', [ApplicantController::class, 'destroy'])->name('delete');

