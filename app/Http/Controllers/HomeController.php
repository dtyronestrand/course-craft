<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()) {
            return $request->user()->is_admin 
                ? redirect()->route('admin.dashboard')
                : redirect()->route('dashboard');
        }
        return Inertia::render('Welcome');
    }
}
