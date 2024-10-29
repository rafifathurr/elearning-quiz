<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\Quiz\QuizController;
use App\Models\Quiz\Quiz;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view(view: 'home');
})->name('home');

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::group(['controller' => \App\Http\Controllers\Admin\Quiz\QuizController::class, 'prefix' => 'quiz', 'as' => 'quiz.'], function () {
        Route::get('append', 'append')->name('append');
        Route::get('start/{quiz}', 'start')->name('start');
        Route::get('play/{quiz}', 'play')->name('play');
        Route::post('answer', 'answer')->name('answer');
        Route::post('finish', 'finish')->name('finish');
    });
    Route::resource('quiz', \App\Http\Controllers\Admin\Quiz\QuizController::class);
});
