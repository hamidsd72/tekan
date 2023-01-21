<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Model\Setting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function controller_title($type)
    {
        if ($type == 'sum') {
            return 'پنل مدیریت';
        } elseif ('single') {
            return 'پنل مدیریت';
        }
    }

    public function controller_paginate()
    {
        $settings = Setting::select('paginate')->latest()->firstOrFail();
        return $settings->paginate;
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin.index');
    }
}
