<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Quiz\QuizController;
use App\Http\Controllers\PaymentPackageController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AspectQuestionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\UserController;

use App\Models\Quiz\Quiz;
use App\Models\Quiz\QuizQuestion;
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
Route::get('/', function () {
    return view(view: 'landingPage');
})->name('landingPage');

Route::get('otp-verify', [AuthController::class, 'showVerifyForm'])->name('otp.verify');
Route::post('otp-verify', [AuthController::class, 'verifyOtp'])->name('otp.verify.post');

Route::group(['prefix' => 'account', 'as' => 'account.'], function () {
    Route::get('create', [UserController::class, 'create'])->name('create');
    Route::post('store', [UserController::class, 'store'])->name('store');
});


Route::group(['middleware' => 'auth'], function () {
    Route::get('home', [DashboardController::class, 'index'])->name('home');
});



Route::group(['middleware' => ['role:admin|user']], function () {
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::group(['controller' => \App\Http\Controllers\Admin\Quiz\QuizController::class, 'prefix' => 'quiz', 'as' => 'quiz.'], function () {
            Route::get('append', 'append')->name('append');
            Route::get('start/{quiz}', 'start')->name('start');
            Route::get('play/{quiz}', 'play')->name('play');
            Route::get('getQuestion/{result}', 'getQuestion')->name('getQuestion');
            Route::get('showQuestion/{quiz}', 'showQuestion')->name('showQuestion');
            Route::post('answer', 'answer')->name('answer');
            Route::post('lastQuestion', 'lastQuestion')->name('lastQuestion');
            Route::post('finish', 'finish')->name('finish');
            Route::get('result/{resultId}', 'showResult')->name('result');
        });
    });

    Route::group(['prefix' => 'quiz', 'as' => 'quiz.'], function () {
        Route::post('auth', [QuizController::class, 'auth'])->name('auth');
        Route::get('list-quiz', [QuizController::class, 'listQuiz'])->name('listQuiz');
        Route::get('history-quiz', [QuizController::class, 'historyQuiz'])->name('historyQuiz');
        Route::get('review-quiz/{id}', [QuizController::class, 'reviewQuiz'])->name('reviewQuiz');
        Route::get('my-test', [QuizController::class, 'myTest'])->name('myTest');
    });
    Route::group(['prefix' => 'order', 'as' => 'order.'], function () {
        Route::get('index', [OrderController::class, 'index'])->name('index');
        Route::post('checkout/{id}', [OrderController::class, 'checkout'])->name('checkout');
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

        Route::group(['controller' => AspectQuestionController::class, 'prefix' => 'aspect', 'as' => 'aspect.'], function () {
            Route::get('datatable', 'dataTable')->name('dataTable');
        });
        Route::resource('aspect', AspectQuestionController::class)->parameters(['aspect' => 'id']);

        Route::group(['controller' => PaymentPackageController::class, 'prefix' => 'payment', 'as' => 'payment.'], function () {
            Route::get('datatable', 'dataTable')->name('dataTable');
        });
        Route::resource('payment', PaymentPackageController::class)->parameters(['payment' => 'id']);

        //question
        Route::group(['controller' => QuestionController::class, 'prefix' => 'question', 'as' => 'question.'], function () {
            Route::get('datatable', 'dataTable')->name('dataTable');
            Route::get('append', 'append')->name('append');
        });
        Route::resource('question', QuestionController::class)->parameters(['question' => 'id']);


        Route::group(['controller' => PackageController::class, 'prefix' => 'package', 'as' => 'package.'], function () {
            Route::get('datatable', 'dataTable')->name('dataTable');
        });
        Route::resource('package', PackageController::class)->parameters(['package' => 'id']);
    });

    Route::group(['prefix' => 'order', 'as' => 'order.'], function () {
        Route::get('list-order', [QuizController::class, 'listOrder'])->name('listOrder');
    });

    Route::group(['prefix' => 'class', 'as' => 'class.'], function () {
        Route::get('list-class', [QuizController::class, 'listClass'])->name('listClass');
    });
});
