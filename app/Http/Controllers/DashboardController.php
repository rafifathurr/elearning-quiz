<?php

namespace App\Http\Controllers;

use App\Models\Package;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data['tests'] = Package::whereNull('class')->whereNull('deleted_at')->get();
        $data['classes'] = Package::whereNotNull('class')->whereNull('deleted_at')->get();

        return view('home', $data);
    }
}
