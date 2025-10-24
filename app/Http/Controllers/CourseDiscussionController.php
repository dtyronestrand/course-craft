<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CourseDiscussion;
use App\Models\ModuleItem;

class CourseDiscussionController extends Controller
{
 public function store(Request $request)
 {
     $request->validate([
         'title' => 'required|string|max:255',
         'prompt' => 'required|string',
         'settings' => 'required|array',
         'module' => 'required',
     ]);

     $courseDiscussion = CourseDiscussion::create([
         'title' => $request->title,
         'prompt' => $request->prompt,
            'settings' => $request->settings,
     ]);

     ModuleItem::create([
         'course_module_id' => $request->module,
         'itemable_id' => $courseDiscussion->id,
         'itemable_type' => CourseDiscussion::class,
     ]);

     return redirect()->back()->with('success', 'Course dicussion created successfully');
 }

    public function update(Request $request, CourseDiscussion $courseDiscussion)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'prompt' => 'required|string',
            'settings' => 'required|array',
        ]);
    
        $courseDiscussion->update([
            'title' => $request->title,
            'prompt' => $request->prompt,
            'settings' => $request->settings,
        ]);
    
        return redirect()->back()->with('success', 'Course discussion updated successfully');
    }

    public function destroy(CourseDiscussion $courseDiscussion)
    {
        $courseDiscussion->delete();

        return redirect()->back()->with('success', 'Course page deleted successfully');
    }
}
