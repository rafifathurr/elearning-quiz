<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Custom render method to handle different HTTP exception statuses.
     */
    public function render($request, Throwable $exception)
    {
        // Handle HTTP Exceptions
        if ($exception instanceof HttpExceptionInterface) {

            // Handle 403 Forbidden error (no access)
            if ($exception->getStatusCode() == 403) {
                if (Auth::check()) {
                    // If the user is authenticated but doesn't have access
                    return redirect()
                        ->back()
                        ->with(['failed' => 'Anda Tidak Memiliki Akses']);
                } else {
                    // If the user is not authenticated, redirect to login page
                    return redirect()->route('login')->with(['error' => 'Silakan login terlebih dahulu']);
                }
            }

            // Handle 404 Not Found error (URL not found)
            if ($exception->getStatusCode() == 404) {
                return redirect()
                    ->back()
                    ->with(['failed' => 'URL Tidak Ditemukan']);
            }

            // Handle 419 Page Expired error (session expired)
            if ($exception->getStatusCode() == 419) {
                return redirect()->route('login')->with(['error' => 'Session Anda telah kedaluwarsa, silakan login kembali']);
            }
        }

        // Default rendering of the exception if it's not handled explicitly
        return parent::render($request, $exception);
    }
}
