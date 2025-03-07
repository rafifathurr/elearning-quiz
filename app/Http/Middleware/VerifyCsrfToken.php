<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'snap/v1.0/transfer-va/inquiry',
        'snap/v1.0/transfer-va/payment',
        'snap/v1.0/access-token/b2b',
        'simulate-signature',
        '/test-signature',
        // '/simulate-signature',
        // '/snap/v1.0/access-token/b2b'

    ];
}
