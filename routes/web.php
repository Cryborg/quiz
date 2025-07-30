<?php

use App\Http\Controllers\GameController;
use App\Models\Theme;
use App\Models\GameSession;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $themes = Theme::where('is_active', true)->withCount('questions')->get();
    $recentSessions = GameSession::with('players')->latest()->take(5)->get();
    $totalQuestions = \App\Models\Question::where('is_active', true)->count();
    
    return view('quiz.home', compact('themes', 'recentSessions', 'totalQuestions'));
})->name('home');

// Routes de jeu
Route::prefix('game')->name('game.')->group(function () {
    Route::get('/create', [GameController::class, 'createSession'])->name('create');
    Route::post('/create', [GameController::class, 'storeSession'])->name('store');
    Route::get('/{session}/lobby', [GameController::class, 'lobby'])->name('lobby');
    Route::post('/{session}/start', [GameController::class, 'startGame'])->name('start');
    Route::get('/{session}/play', [GameController::class, 'play'])->name('play');
    Route::post('/{session}/score', [GameController::class, 'updateScore'])->name('score');
    Route::post('/{session}/finish', [GameController::class, 'finishGame'])->name('finish');
    Route::get('/{session}/results', [GameController::class, 'results'])->name('results');
});
