<?php

namespace App\Http\Controllers;

use App\Models\OrderPackage;
use App\Models\Package;
use App\Models\Result;
use App\Models\TypePackage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $data['tests'] = Package::where(function ($query) {
            $query->whereNull('class')
                ->orWhere('class', 0);
        })->whereNull('deleted_at')->get();

        $data['classes'] = Package::where('class', '>', 0)->whereNull('deleted_at')->get();

        return view('home', $data);
    }

    public function landingPage()
    {
        if (Auth::check()) {
            if (User::find(Auth::user()->id)->hasRole('user')) {
                $result = Result::where('user_id', Auth::id())
                    ->whereNull('finish_time')
                    ->first();
                if ($result) {
                    Auth::logout();
                    return redirect()->route('landingPage');
                } else {
                    return redirect()->route('home');
                }
            }
            return redirect()->route('home');
        }

        $data['type_package'] = TypePackage::whereNull('deleted_at')->get();

        return view('landingPage', $data);
    }

    public function contact()
    {
        return view('contact');
    }
}
