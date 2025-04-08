<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Quiz\QuizController;
use App\Http\Controllers\PaymentPackageController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AspectQuestionController;
use App\Http\Controllers\BrivaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DateClassController;
use App\Http\Controllers\KecermatanController;
use App\Http\Controllers\myClassAdminController;
use App\Http\Controllers\myClassController;
use App\Http\Controllers\myTestController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PackageMemberController;
use App\Http\Controllers\ReportController;
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

//tes briva
// Route::post('/briva/inquiry', [BrivaController::class, 'inquiry']);
// Route::post('/briva/payment', [BrivaController::class, 'payment']);

Route::prefix('snap/v1.0')->group(function () {
    Route::post('/transfer-va/inquiry', [BrivaController::class, 'inquiry']);
    Route::post('/transfer-va/payment', [BrivaController::class, 'payment']);
    Route::post('/access-token/b2b', [BrivaController::class, 'getToken']);
});

Route::post('/simulate-signature', [BrivaController::class, 'simulateSignature']);
Route::post('/snap/v1.0/access-token/b2b', [BrivaController::class, 'getAccessToken'])
    ->name('bri.access_token');
Route::post('/snap/v1.0/generate-signature', [BrivaController::class, 'generateSignature'])
    ->name('bri.generate_signature');
Route::post('/test-signature', [BrivaController::class, 'generateSignatureV2']);



//simulasi encrypt decrypt signature
//Route::get('/simulate-signature', [BrivaController::class, 'simulateSignature'])->middleware('api');





//Google Login
Route::get('/auth/google', [AuthController::class, 'redirect']);
Route::get('/auth/google/callback', [AuthController::class, 'callback']);


//Register Akun Google
Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::get('register', [AuthController::class, 'create'])->name('create');
    Route::post('store', [AuthController::class, 'storeDataGoogle'])->name('storeDataGoogle');
});


//Login
Route::get('login', function () {
    if (Auth::check()) {
        return redirect()->back()->with('failed', 'Anda sudah login. Harap logout terlebih dahulu.');
    }
    return view('auth.login');
})->name('login');

Route::post('authenticate', [AuthController::class, 'authenticate'])->name('authenticate');


//Halaman Utama
Route::get('/', [DashboardController::class, 'landingPage'])->name('landingPage');

//Save Chart
Route::post('/save-chart', [DashboardController::class, 'saveChart']);


//Kirim OTP
Route::get('otp-verify', [AuthController::class, 'showVerifyForm'])->name('otp.verify');
Route::post('otp-verify', [AuthController::class, 'verifyOtp'])->name('otp.verify.post');


//Register Akun
Route::group(['prefix' => 'account', 'as' => 'account.'], function () {
    Route::get('create', [UserController::class, 'create'])->name('create');
    Route::post('store', [UserController::class, 'store'])->name('store');
});


//Lupa Password
Route::group(['controller' => AuthController::class, 'prefix' => 'password', 'as' => 'password.'], function () {
    Route::get('forgot-password',  'showForgotPasswordForm')->name('request');
    Route::post('forgot-password',  'sendResetLinkEmail')->name('email');
    Route::get('reset-password/{token}',  'showResetForm')->name('reset');
    Route::post('reset-password',  'resetPassword')->name('update');
});


//Pengguna Login
Route::group(['middleware' => 'auth'], function () {
    Route::get('home', [DashboardController::class, 'index'])->name('home');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('contact', [DashboardController::class, 'contact'])->name('contact');

    // Edit Akun
    Route::group(['prefix' => 'my-account', 'as' => 'my-account.'], function () {
        Route::get('/', [UserController::class, 'show'])->name('show');
        Route::get('edit', [UserController::class, 'edit'])->name('edit');
        Route::match(['put', 'patch'], '{id}', [UserController::class, 'updatePassword'])->name('updatePassword');
    });
});

//Order User Konselor
Route::group(['middleware' => ['role:user|counselor']], function () {

    Route::group(['controller' => OrderController::class, 'prefix' => 'order', 'as' => 'order.'], function () {
        //Order User
        Route::get('index', 'index')->name('index');
        Route::get('datatable', 'dataTable')->name('dataTable');
        Route::get('get-schedule/{id}', 'getSchedule')->name('getSchedule');
        Route::get('history', 'history')->name('history');
        Route::get('detail-pembayaran/{id}', 'detailPayment')->name('detailPayment');
        Route::match(['put', 'patch'], 'upload-payment/{id}', 'uploadPayment')->name('uploadPayment');
        Route::post('checkout/{id}', 'checkout')->name('checkout');
        Route::post('payment/{id}', 'payment')->name('payment');
        Route::delete('delete/{id}', 'destroy')->name('destroy');

        Route::get('get-users', 'getUser')->name('getUser');
        Route::post('checkout-counselor/{id}', 'checkoutCounselor')->name('checkoutCounselor');

        Route::get('view-payment/{id}',  'viewPayment')->name('viewPayment');
    });
});


//Hanya User
Route::group(['middleware' => ['role:user']], function () {

    //Daftar Test User
    Route::group(['controller' => myTestController::class, 'prefix' => 'mytest', 'as' => 'mytest.'], function () {
        Route::get('datatable', 'dataTable')->name('dataTable');
        Route::get('index', 'index')->name('index');
    });

    // myClass User
    Route::group(['controller' => myClassController::class, 'prefix' => 'myclass', 'as' => 'myclass.'], function () {
        Route::get('datatable', 'dataTable')->name('dataTable');
        Route::get('index', 'index')->name('index');
        Route::get('detail/{orderId}/{packageId}/{classId}', 'detail')->name('detail');
        Route::get('datatable2/{orderId}/{packageId}/{classId}', 'dataTableDetail')->name('dataTableDetail');
        Route::get('attendance/{orderPackageId}', 'dataTableAttendance')->name('dataTableAttendance');
    });
});


//Admin | User | question-operator
Route::group(['middleware' => ['role:admin|user|question-operator|manager']], function () {
    // Play Quiz
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

    // Udah ga kepake
    Route::group(['prefix' => 'quiz', 'as' => 'quiz.'], function () {
        Route::post('auth', [QuizController::class, 'auth'])->name('auth');
        Route::get('list-quiz', [QuizController::class, 'listQuiz'])->name('listQuiz');
        Route::get('history-quiz', [QuizController::class, 'historyQuiz'])->name('historyQuiz');
        Route::get('review-quiz/{id}', [QuizController::class, 'reviewQuiz'])->name('reviewQuiz');
    });

    // Tes Kecermatan
    Route::group(['controller' => KecermatanController::class, 'prefix' => 'kecermatan', 'as' => 'kecermatan.'], function () {
        Route::get('play/{quiz}', 'play')->name('play');
        Route::get('getQuestion/{result}', 'getQuestion')->name('getQuestion');
        Route::post('answer', 'answer')->name('answer');
        Route::post('nextQuestion', 'nextQuestion')->name('nextQuestion');
        Route::post('finish', 'finish')->name('finish');
        Route::get('result/{resultId}', 'showResult')->name('result');
    });
});




// Admin | Finance
Route::group(['middleware' => ['role:admin|finance|manager']], function () {
    // Kelola Daftar Order
    Route::group(['controller' => OrderController::class, 'prefix' => 'order', 'as' => 'order.'], function () {
        Route::get('list-order', 'listOrder')->name('listOrder');
        Route::get('detail-order/{id}', 'detailOrder')->name('detailOrder');
        Route::get('datatable-list-order', 'dataTableListOrder')->name('dataTableListOrder');
        Route::post('approve/{id}', 'approve')->name('approve');
        Route::post('reject/{id}', 'reject')->name('reject');
        Route::get('download-payment/{id}',  'downloadProof')->name('downloadPayment');
    });
});


// Admin | Konselor
Route::group(['middleware' => ['role:admin|counselor|manager']], function () {
    Route::group(['prefix' => 'master', 'as' => 'master.'], function () {
        // Kelola Jadwal Kelas
        Route::group(['controller' => DateClassController::class, 'prefix' => 'dateclass', 'as' => 'dateclass.'], function () {
            Route::get('datatable', 'dataTable')->name('dataTable');
        });
        Route::resource('dateclass', DateClassController::class)->parameters(['dateclass' => 'id']);
    });

    // Review Hasil Test
    Route::group(['controller' => myTestController::class, 'prefix' => 'mytest', 'as' => 'mytest.'], function () {
        Route::get('datatable-history', 'dataTableHistory')->name('dataTableHistory');
        Route::get('history', 'history')->name('history');
        Route::get('review/{id}', 'review')->name('review');
        Route::delete('{id}', 'destroy')->name('destroy');
    });
});

// Konselor | Kelas Operator
Route::group(['middleware' => ['role:counselor|class-operator|manager']], function () {
    // Kelola Kelas Konselor
    Route::group(['controller' => myClassAdminController::class, 'prefix' => 'class', 'as' => 'class.'], function () {
        Route::get('datatable', 'dataTable')->name('dataTable');
        Route::get('get-order-packages/{package_id}/{date_in_class}', 'getOrderPackages')
            ->where('date_in_class', '.*') // Tambahkan regex agar bisa menerima karakter khusus
            ->name('getOrderPackages');

        Route::get('get-date-classes/{package_id}', 'getDateClasses')->name('getDateClasses');
        Route::post('store-attendance', 'storeAttendance')->name('storeAttendance');
        Route::post('update-attendance', 'updateAttendance')->name('updateAttendance');
        Route::post('store-test', 'storeTest')->name('storeTest');
        Route::post('update-test', 'updateTest')->name('updateTest');
        Route::post('store-member', 'storeMember')->name('storeMember');
        Route::delete('remove-member/{index}', 'removeMember')->name('removeMember');
        Route::get('getPackages', 'getPackages')->name('getPackages');
        Route::get('exportData', 'exportData')->name('exportData');
    });

    Route::get('/class/get-test/{id}', function ($id) {
        return \App\Models\OrderDetail::find($id);
    });

    Route::resource('class', myClassAdminController::class)->parameters(['class' => 'id']);

    Route::group(['prefix' => 'master', 'as' => 'master.'], function () {
        //Member Paket
        Route::group(['controller' => PackageMemberController::class, 'prefix' => 'member', 'as' => 'member.'], function () {
            Route::get('datatable', 'dataTable')->name('dataTable');
            Route::get('index', 'index')->name('index');
            Route::get('export', 'export')->name('export');
            Route::get('pdf/{id}', 'exportPdf')->name('pdf');
            Route::get('get-date', 'getDateClass')->name('getDateClass');
        });
    });
});


// Hanya Admin
Route::group(['middleware' => ['role:admin|manager']], function () {

    Route::group(['prefix' => 'master', 'as' => 'master.'], function () {
        //Pengguna
        Route::group(['controller' => UserController::class, 'prefix' => 'user', 'as' => 'user.'], function () {
            Route::get('datatable', 'dataTable')->name('dataTable');
            Route::post('update-status/{id}', 'updateStatus')->name('updateStatus');
        });
        Route::resource('user', UserController::class)->parameters(['user' => 'id']);

        //Paket Pembayaran (Udah Ga Kepake)
        Route::group(['controller' => PaymentPackageController::class, 'prefix' => 'payment', 'as' => 'payment.'], function () {
            Route::get('datatable', 'dataTable')->name('dataTable');
        });
        Route::resource('payment', PaymentPackageController::class)->parameters(['payment' => 'id']);
    });

    Route::group(['controller' => ReportController::class, 'prefix' => 'laporan', 'as' => 'laporan.'], function () {
        Route::get('index', 'index')->name('index');
        Route::get('export', 'export')->name('export');
    });
});


// Admin | Package Manager
Route::group(['middleware' => ['role:admin|package-manager|manager']], function () {

    Route::group(['prefix' => 'master', 'as' => 'master.'], function () {

        //Paket
        Route::group(['controller' => PackageController::class, 'prefix' => 'package', 'as' => 'package.'], function () {
            Route::get('datatable', 'dataTable')->name('dataTable');
            Route::get('exportData', 'exportData')->name('exportData');
            Route::post('update-status/{id}', 'updateStatus')->name('updateStatus');
        });
        Route::resource('package', PackageController::class)->parameters(['package' => 'id']);

        //Tipe Paket
        Route::group(['controller' => TypePackageController::class, 'prefix' => 'typePackage', 'as' => 'typePackage.'], function () {
            Route::get('datatable', 'dataTable')->name('dataTable');
        });
        Route::resource('typePackage', TypePackageController::class)->parameters(['typePackage' => 'id']);
    });
});


// Admin | Question Operator
Route::group(['middleware' => ['role:admin|question-operator|manager']], function () {

    Route::group(['prefix' => 'master', 'as' => 'master.'], function () {
        //Bank Soal
        Route::group(['controller' => QuestionController::class, 'prefix' => 'question', 'as' => 'question.'], function () {
            Route::get('datatable', 'dataTable')->name('dataTable');
            Route::get('append', 'append')->name('append');
        });
        Route::resource('question', QuestionController::class)->parameters(['question' => 'id']);

        //Aspek Pertanyaan
        Route::group(['controller' => AspectQuestionController::class, 'prefix' => 'aspect', 'as' => 'aspect.'], function () {
            Route::get('datatable', 'dataTable')->name('dataTable');
            Route::get('get-aspect', 'getAspectsByTypeAspect')->name('getAspectsByTypeAspect');
        });
        Route::resource('aspect', AspectQuestionController::class)->parameters(['aspect' => 'id']);

        //Tes Kecermatan
        Route::group(['controller' => KecermatanController::class, 'prefix' => 'kecermatan', 'as' => 'kecermatan.'], function () {
            Route::get('create', 'create')->name('create');
            Route::post('store', 'store')->name('store');
            Route::get('edit/{quiz}', 'edit')->name('edit');
            Route::patch('update/{quiz}', 'update')->name('update');
        });
    });

    //Tes Selain Kecermatan
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::resource('quiz', \App\Http\Controllers\Admin\Quiz\QuizController::class);
    });
});

Route::fallback(function () {
    abort(404);  // Mengarahkan ke halaman 404
});
