<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CoursePage;
use App\Models\ModuleItem;

class CoursePageController extends Controller
{
 public function store(Request $request)
 {
     $request->validate([
         'title' => 'required|string|max:255',
         'content' => 'required|string',
         'module' => 'required',
     ]);

     $coursePage = CoursePage::create([
         'title' => $request->title,
         'content' => $request->content,
     ]);

     $maxOrderIndex = ModuleItem::where('course_module_id', $request->module)->max('order_index') ?? -1;

     ModuleItem::create([
         'course_module_id' => $request->module,
         'itemable_id' => $coursePage->id,
         'itemable_type' => 'page',
         'order_index' => $maxOrderIndex + 1,
     ]);

     return redirect()->back()->with('success', 'Course page created successfully');
 }

    public function update(Request $request, CoursePage $coursePage)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);
    
        $coursePage->update([
            'title' => $request->title,
            'content' => $request->content,
        ]);
    
        return redirect()->back()->with('success', 'Course page updated successfully');
    }

    public function destroy(CoursePage $coursePage)
    {
        $coursePage->delete();

        return redirect()->back()->with('success', 'Course page deleted successfully');
    }
}
