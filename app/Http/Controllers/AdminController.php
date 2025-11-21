<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller
{
  public function index()
  {
    return Inertia::render('admin/Dashboard');
  }
  public function courses()
  {
    return Inertia::render('admin/Courses');
  }
}
