<?php

namespace App\Http\Controllers;

use App\Models\CourseDiscussion;
use App\Services\CourseDiscussionService;
use Illuminate\Http\Request;

class CourseDiscussionController extends Controller
{
    protected $courseDiscussionService;

    public function __construct(CourseDiscussionService $courseDiscussionService)
    {
        $this->courseDiscussionService = $courseDiscussionService;
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'prompt' => 'required|string',
            'settings' => 'required|array',
            'module' => 'required',
        ]);

        $this->courseDiscussionService->createDiscussion($request->all());

        return redirect()->back()->with('success', 'Course discussion created successfully');
    }

    public function update(Request $request, CourseDiscussion $courseDiscussion)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'prompt' => 'required|string',
            'settings' => 'required|array',
        ]);

        $this->courseDiscussionService->updateDiscussion($courseDiscussion, $request->all());

        return redirect()->back()->with('success', 'Course discussion updated successfully');
    }

    public function destroy(CourseDiscussion $courseDiscussion)
    {
        $this->courseDiscussionService->deleteDiscussion($courseDiscussion);

        return redirect()->back()->with('success', 'Course discussion deleted successfully');
    }
}
