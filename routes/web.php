<?php

use App\Http\Controllers\AkunController;
use App\Http\Controllers\ProfessionController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::view('/', 'auth.landing')->name('landing');
    Route::get('/login', [AkunController::class, 'formLogin'])->name('login');
    Route::post('/login', [AkunController::class, 'login'])->middleware('throttle:10,1');
    Route::get('/register', [AkunController::class, 'formSignIn'])->name('register');
    Route::post('/register', [AkunController::class, 'signIn'])->middleware('throttle:10,1');
});

Route::middleware('auth')->group(function () {
    Route::get('/welcome', [AkunController::class, 'welcome'])->name('welcome');
    Route::post('/logout', [AkunController::class, 'logout'])->name('logout');

    Route::get('/profile', [AkunController::class, 'loadDataProfil'])->name('profile');
    Route::get('/settings', [AkunController::class, 'settings'])->name('settings');
    Route::put('/settings/username', [AkunController::class, 'updateUsername'])->name('settings.username');
    Route::put('/settings/password', [AkunController::class, 'updatePassword'])->name('settings.password');

    Route::post('/skills', [AkunController::class, 'createSkill'])->name('skills.store');
    Route::put('/skills/{skill}', [AkunController::class, 'updateSkill'])->name('skills.update');
    Route::delete('/skills/{skill}', [AkunController::class, 'deleteSkill'])->name('skills.destroy');

    Route::post('/pengalaman', [AkunController::class, 'createPengalaman'])->name('pengalaman.store');
    Route::put('/pengalaman/{pengalaman}', [AkunController::class, 'updatePengalaman'])->name('pengalaman.update');
    Route::delete('/pengalaman/{pengalaman}', [AkunController::class, 'deletePengalaman'])->name('pengalaman.destroy');

    Route::get('/career-match', [ProfessionController::class, 'hitungPersentaseCareerMatch'])->name('career-match');

    Route::get('/search', [ProfessionController::class, 'professionKeywordSearch'])->name('search');
    Route::get('/bookmarks', [ProfessionController::class, 'readBookmarked'])->name('bookmarks');
    Route::get('/profesi/{profesi}', [ProfessionController::class, 'loadDetailProfesi'])->name('profesi.show');
    Route::get('/profesi/{profesi}/review', [ProfessionController::class, 'loadReviewProfesi'])->name('profesi.review');
    Route::post('/profesi/{profesi}/bookmark', [ProfessionController::class, 'saveBookmarked'])->name('bookmark.store');
    Route::delete('/profesi/{profesi}/bookmark', [ProfessionController::class, 'removeBookmark'])->name('bookmark.destroy');
    Route::get('/profesi/{profesi}/day-in-the-life', [ProfessionController::class, 'dayInTheLife'])->name('profesi.day');
    Route::get('/rutinitas/{rutinitas}', [ProfessionController::class, 'detailRutinitas'])->name('rutinitas.show');
    Route::get('/fase/{fase}', [ProfessionController::class, 'detailProyek'])->name('fase.show');
});
