<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Quiz\QuizController;
use App\Http\Controllers\PaymentPackageController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AspectQuestionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DateClassController;
use App\Http\Controllers\KecermatanController;
use App\Http\Controllers\myClassAdminController;
use App\Http\Controllers\myClassController;
use App\Http\Controllers\myTestController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PackageMemberController;
use App\Http\Controllers\TypePackageController;
use App\Http\Controllers\UserController;

use App\Models\Quiz\Quiz;
use App\Models\Quiz\QuizQuestion;
use App\Models\Result;
use App\Models\ResultDetail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;


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

//Google Login
Route::get('/auth/google', [AuthController::class, 'redirect']);
Route::get('/auth/google/callback', [AuthController::class, 'callback']);


Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::get('register', [AuthController::class, 'create'])->name('create');
    Route::post('store', [AuthController::class, 'storeDataGoogle'])->name('storeDataGoogle');
});


Route::get('login', function () {
    if (Auth::check()) {
        return redirect()->back()->with('failed', 'Anda sudah login. Harap logout terlebih dahulu.');
    }
    return view('auth.login');
})->name('login');

Route::post('authenticate', [AuthController::class, 'authenticate'])->name('authenticate');


Route::get('/', [DashboardController::class, 'landingPage'])->name('landingPage');

Route::get('otp-verify', [AuthController::class, 'showVerifyForm'])->name('otp.verify');
Route::post('otp-verify', [AuthController::class, 'verifyOtp'])->name('otp.verify.post');

Route::group(['prefix' => 'account', 'as' => 'account.'], function () {
    Route::get('create', [UserController::class, 'create'])->name('create');
    Route::post('store', [UserController::class, 'store'])->name('store');
});

Route::group(['controller' => AuthController::class, 'prefix' => 'password', 'as' => 'password.'], function () {
    Route::get('forgot-password',  'showForgotPasswordForm')->name('request');
    Route::post('forgot-password',  'sendResetLinkEmail')->name('email');
    Route::get('reset-password/{token}',  'showResetForm')->name('reset');
    Route::post('reset-password',  'resetPassword')->name('update');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('home', [DashboardController::class, 'index'])->name('home');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('contact', [DashboardController::class, 'contact'])->name('contact');
});

//Admin | User
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
    });

    Route::group(['controller' => OrderController::class, 'prefix' => 'order', 'as' => 'order.'], function () {
        Route::get('datatable', 'dataTable')->name('dataTable');
        Route::get('get-schedule/{id}', 'getSchedule')->name('getSchedule');
        Route::get('history', 'history')->name('history');
        Route::get('detail-transfer/{id}', 'detailTransfer')->name('detailTransfer');
        Route::match(['put', 'patch'], 'upload-payment/{id}', 'uploadPayment')->name('uploadPayment');
        Route::post('checkout/{id}', 'checkout')->name('checkout');
        Route::post('payment/{id}', 'payment')->name('payment');
        Route::delete('delete/{id}', 'destroy')->name('destroy');
    });

    Route::group(['controller' => KecermatanController::class, 'prefix' => 'kecermatan', 'as' => 'kecermatan.'], function () {
        Route::get('play/{quiz}', 'play')->name('play');
        Route::get('getQuestion/{result}', 'getQuestion')->name('getQuestion');
        Route::post('answer', 'answer')->name('answer');
        Route::post('nextQuestion', 'nextQuestion')->name('nextQuestion');
        Route::post('finish', 'finish')->name('finish');
        Route::get('result/{resultId}', 'showResult')->name('result');
    });

    Route::group(['controller' => myClassController::class, 'prefix' => 'myclass', 'as' => 'myclass.'], function () {
        Route::get('datatable', 'dataTable')->name('dataTable');
        Route::get('index', 'index')->name('index');
        Route::get('detail/{orderId}/{packageId}', 'detail')->name('detail');
        Route::get('datatable2/{orderId}/{packageId}', 'dataTableDetail')->name('dataTableDetail');
    });
});

//Admin | User
Route::group(['middleware' => ['role:user|finance|counselor|package-manager|question-operator']], function () {
    Route::group(['prefix' => 'my-account', 'as' => 'my-account.'], function () {
        Route::get('/', [UserController::class, 'show'])->name('show');
        Route::get('edit', [UserController::class, 'edit'])->name('edit');
        Route::match(['put', 'patch'], '{id}', [UserController::class, 'update'])->name('update');
    });
});
//Admin | User | Finance
Route::group(['middleware' => ['role:admin|user|finance']], function () {
    Route::group(['controller' => OrderController::class, 'prefix' => 'order', 'as' => 'order.'], function () {
        Route::get('index', 'index')->name('index');
    });
});

// Admin | Finance
Route::group(['middleware' => ['role:admin|finance']], function () {
    Route::group(['controller' => OrderController::class, 'prefix' => 'order', 'as' => 'order.'], function () {
        Route::get('datatable2', 'dataTable2')->name('dataTable2');
        Route::post('approve/{id}', 'approve')->name('approve');
        Route::post('reject/{id}', 'reject')->name('reject');
    });
});

//Admin | User | Konselor
Route::group(['middleware' => ['role:admin|user|counselor']], function () {
    Route::group(['controller' => myTestController::class, 'prefix' => 'mytest', 'as' => 'mytest.'], function () {
        Route::get('datatable', 'dataTable')->name('dataTable');
        Route::get('index', 'index')->name('index');
        Route::get('datatable-history', 'dataTableHistory')->name('dataTableHistory');
        Route::get('history', 'history')->name('history');
        Route::get('review/{id}', 'review')->name('review');
        Route::delete('{id}', 'destroy')->name('destroy');
    });
});

// Admin | Konselor
Route::group(['middleware' => ['role:admin|counselor']], function () {
    Route::group(['prefix' => 'master', 'as' => 'master.'], function () {

        Route::group(['controller' => DateClassController::class, 'prefix' => 'dateclass', 'as' => 'dateclass.'], function () {
            Route::get('datatable', 'dataTable')->name('dataTable');
        });
        Route::resource('dateclass', DateClassController::class)->parameters(['dateclass' => 'id']);
    });
});

// Konselor
Route::group(['middleware' => ['role:counselor']], function () {
    Route::group(['controller' => myClassAdminController::class, 'prefix' => 'class', 'as' => 'class.'], function () {
        Route::get('datatable', 'dataTable')->name('dataTable');
        Route::post('store-attendance', 'storeAttendance')->name('storeAttendance');
        Route::post('update-attendance', 'updateAttendance')->name('updateAttendance');
        Route::post('store-test', 'storeTest')->name('storeTest');
        Route::post('store-member', 'storeMember')->name('storeMember');
        Route::delete('remove-member/{index}', 'removeMember')->name('removeMember');
    });
    Route::resource('class', myClassAdminController::class)->parameters(['class' => 'id']);
});


// Hanya Admin
Route::group(['middleware' => ['role:admin']], function () {
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::resource('quiz', \App\Http\Controllers\Admin\Quiz\QuizController::class);
    });

    Route::group(['prefix' => 'master', 'as' => 'master.'], function () {
        Route::group(['controller' => UserController::class, 'prefix' => 'user', 'as' => 'user.'], function () {
            Route::get('datatable', 'dataTable')->name('dataTable');
        });
        Route::resource('user', UserController::class)->parameters(['user' => 'id']);

        Route::group(['controller' => DateClassController::class, 'prefix' => 'dateclass', 'as' => 'dateclass.'], function () {
            Route::get('datatable', 'dataTable')->name('dataTable');
        });
        Route::resource('dateclass', DateClassController::class)->parameters(['dateclass' => 'id']);

        Route::group(['controller' => PaymentPackageController::class, 'prefix' => 'payment', 'as' => 'payment.'], function () {
            Route::get('datatable', 'dataTable')->name('dataTable');
        });
        Route::resource('payment', PaymentPackageController::class)->parameters(['payment' => 'id']);

        Route::group(['controller' => PackageMemberController::class, 'prefix' => 'member', 'as' => 'member.'], function () {
            Route::get('datatable', 'dataTable')->name('dataTable');
            Route::get('index', 'index')->name('index');
            Route::get('export', 'export')->name('export');
        });

        Route::group(['controller' => KecermatanController::class, 'prefix' => 'kecermatan', 'as' => 'kecermatan.'], function () {
            Route::get('create', 'create')->name('create');
            Route::post('store', 'store')->name('store');
            Route::get('edit/{quiz}', 'edit')->name('edit');
            Route::patch('update/{quiz}', 'update')->name('update');
        });
    });
});

// Admin | Package Manager
Route::group(['middleware' => ['role:admin|package-manager']], function () {

    Route::group(['prefix' => 'master', 'as' => 'master.'], function () {

        Route::group(['controller' => PackageController::class, 'prefix' => 'package', 'as' => 'package.'], function () {
            Route::get('datatable', 'dataTable')->name('dataTable');
            Route::post('update-status/{id}', 'updateStatus')->name('updateStatus');
        });
        Route::resource('package', PackageController::class)->parameters(['package' => 'id']);

        Route::group(['controller' => TypePackageController::class, 'prefix' => 'typePackage', 'as' => 'typePackage.'], function () {
            Route::get('datatable', 'dataTable')->name('dataTable');
        });
        Route::resource('typePackage', TypePackageController::class)->parameters(['typePackage' => 'id']);
    });
});

// Admin | Question Operator
Route::group(['middleware' => ['role:admin|question-operator']], function () {
    Route::group(['prefix' => 'master', 'as' => 'master.'], function () {
        Route::group(['controller' => QuestionController::class, 'prefix' => 'question', 'as' => 'question.'], function () {
            Route::get('datatable', 'dataTable')->name('dataTable');
            Route::get('append', 'append')->name('append');
        });
        Route::resource('question', QuestionController::class)->parameters(['question' => 'id']);

        Route::group(['controller' => AspectQuestionController::class, 'prefix' => 'aspect', 'as' => 'aspect.'], function () {
            Route::get('datatable', 'dataTable')->name('dataTable');
            Route::get('get-aspect', 'getAspectsByTypeAspect')->name('getAspectsByTypeAspect');
        });
        Route::resource('aspect', AspectQuestionController::class)->parameters(['aspect' => 'id']);
    });
});
