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

    if ($request->objectives) {
        foreach ($request->objectives as $objective) {
            $course->objectives()->create([
                'number' => $objective['number'],
                'objective' => $objective['objective'],
            ]);
        }
    }
    
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
        $course->load(['modules.courseObjectives', 'modules.assessments.objectives', 'modules.module_objectives', 'modules.instructions', 'modules.materials', 'modules.needs','users', 'modules.items.itemable', 'objectives']);
        $numberOfModules = $course->modules()->count();
        return Inertia::render('courses/Show', [
            'course' => $course,
            'numberOfModules' => $numberOfModules,
        ]);
    }
    public function map(Course $course)
    {
        $course->load(['modules.courseObjectives', 'modules.assessments.objectives', 'modules.module_objectives', 'modules.instructions', 'modules.materials', 'modules.needs','users', 'objectives']);
        $numberOfModules = $course->modules()->count();
        return Inertia::render('courses/Map', [
            'course' => $course,
            'numberOfModules' => $numberOfModules,
        ]);
    }

    public function storyboard(Course $course)
    {
        $course->load(['modules.courseObjectives', 'modules.module_objectives', 'users', 'modules.items.itemable','objectives']);
        $numberOfModules = $course->modules()->count();
        return Inertia::render('courses/Storyboard', [
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
        $request->validate([
            'prefix' => 'required|string|max:10',
            'number' => 'required|string|max:10',
            'title' => 'required|string|max:255',
            'objectives' => 'nullable|array',
            'users' => 'nullable|array',
        ]);

        $course->update([
            'prefix' => $request->prefix,
            'number' => $request->number,
            'title' => $request->title,
        ]);

        if ($request->objectives) {
            $course->objectives()->delete();
            foreach ($request->objectives as $objective) {
                $course->objectives()->create([
                    'number' => $objective['number'],
                    'objective' => $objective['objective'],
                ]);
            }
        }
        
        if ($request->users) {
            $course->users()->detach();
            foreach ($request->users as $user) {
                $course->users()->attach($user['id'], ['role' => $user['role']]);
            }
        }
        
        return to_route('dashboard');
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
