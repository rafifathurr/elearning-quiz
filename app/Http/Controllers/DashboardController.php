<?php

namespace App\Http\Controllers;

use App\Models\OrderPackage;
use App\Models\Package;

use Illuminate\Http\Request;

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
        $data['tests'] = Package::where(function ($query) {
            $query->whereNull('class')
                ->orWhere('class', 0);
        })->whereNull('deleted_at')->get();
        $data['best_seller'] = Package::withCount(['orderPackage' => function ($query) {
            $query->whereHas('order', function ($subQuery) {
                $subQuery->where('status', 100);
            });
        }])
            ->where('class', '>', 0)
            ->whereNull('deleted_at')
            ->orderBy('order_package_count', 'DESC') ///order_package_count = dari nama relasi orderPackage ditambah count dari withCount
            ->take(3)
            ->get();

        $data['classes'] = Package::where('class', '>', 0)->whereNull('deleted_at')->get();

        return view('landingPage', $data);
    }

    public function contact()
    {
        return view('contact');
    }
}
