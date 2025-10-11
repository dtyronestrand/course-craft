<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;


class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
return Inertia::render('Dashboard', [
    'courses' => $request->user()->courses()->get(),
]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      $request->validate([
        'prefix' => 'required|string|max:10',
        'number' => 'required|string|max:10',
        'title' => 'required|string|max:255',
        'objectives' => 'nullable|array',
        'users' => 'nullable|array',
    ]);

    $course = Course::create([
        'prefix' => $request->prefix,
        'number' => $request->number,
        'title' => $request->title,
    
    ]);
    
    if ($request->users) {
        foreach ($request->users as $user) {
            $course->users()->attach($user['id'], ['role' => $user['role']]);
        }
    }
    return to_route('dashboard');


    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        $course->load(['modules', 'users']);
        $numberOfModules = $course->modules()->count();
        return Inertia::render('courses/Show', [
            'course' => $course,
            'numberOfModules' => $numberOfModules,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
      $course->delete();
      return to_route('dashboard');
    }
}
