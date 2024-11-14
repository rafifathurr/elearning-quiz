<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Quiz\QuizController;
use App\Http\Controllers\PaymentPackageController;
use App\Http\Controllers\TypeQuizController;
use App\Http\Controllers\UserController;
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



Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('authenticate', [AuthController::class, 'authenticate'])->name('authenticate');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

Route::group(['prefix' => 'account', 'as' => 'account.'], function () {
    Route::get('create', [UserController::class, 'create'])->name('create');
    Route::post('store', [UserController::class, 'store'])->name('store');
});


Route::group(['middleware' => 'auth'], function () {
    Route::get('/', function () {
        return view(view: 'home');
    })->name('home');
});

Route::group(['middleware' => ['role:user']], function () {
    Route::group(['prefix' => 'quiz', 'as' => 'quiz.'], function () {
        Route::post('auth', [QuizController::class, 'auth'])->name('auth');
        Route::get('list-quiz', [QuizController::class, 'listQuiz'])->name('listQuiz');
        Route::get('history-quiz', [QuizController::class, 'historyQuiz'])->name('historyQuiz');
        Route::get('review-quiz/{id}', [QuizController::class, 'reviewQuiz'])->name('reviewQuiz');
    });
});

Route::group(['middleware' => ['role:admin|user']], function () {
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::group(['controller' => \App\Http\Controllers\Admin\Quiz\QuizController::class, 'prefix' => 'quiz', 'as' => 'quiz.'], function () {
            Route::get('append', 'append')->name('append');
            Route::get('start/{quiz}', 'start')->name('start');
            Route::get('play/{quiz}', 'play')->name('play');
            Route::post('answer', 'answer')->name('answer');
            Route::match(['put', 'patch'], 'finish/{quiz}', 'finish')->name('finish');
        });
    });
});

Route::group(['middleware' => ['role:admin']], function () {
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::resource('quiz', \App\Http\Controllers\Admin\Quiz\QuizController::class);
    });

    Route::group(['prefix' => 'master', 'as' => 'master.'], function () {
        Route::group(['controller' => UserController::class, 'prefix' => 'user', 'as' => 'user.'], function () {
            Route::get('datatable', 'dataTable')->name('dataTable');
        });
        Route::resource('user', UserController::class)->parameters(['user' => 'id']);

        Route::group(['controller' => TypeQuizController::class, 'prefix' => 'category', 'as' => 'category.'], function () {
            Route::get('datatable', 'dataTable')->name('dataTable');
        });
        Route::resource('category', TypeQuizController::class)->parameters(['category' => 'id']);

        Route::group(['controller' => PaymentPackageController::class, 'prefix' => 'payment', 'as' => 'payment.'], function () {
            Route::get('datatable', 'dataTable')->name('dataTable');
        });
        Route::resource('payment', PaymentPackageController::class)->parameters(['payment' => 'id']);

        //question
        Route::group(['controller' => PaymentPackageController::class, 'prefix' => 'question', 'as' => 'question.'], function () {
            Route::get('datatable', 'dataTable')->name('dataTable');
        });
        Route::resource('question', PaymentPackageController::class)->parameters(['question' => 'id']);
    });
});
