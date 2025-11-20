<?php

namespace App\Http\Controllers;

use App\Services\GoogleDocGenerationService;
use Illuminate\Http\Request;

class GoogleDocController extends Controller
{
    protected $googleDocGenerationService;

    public function __construct(GoogleDocGenerationService $googleDocGenerationService)
    {
        $this->googleDocGenerationService = $googleDocGenerationService;
    }

    public function generate(Request $request)
    {
        $result = $this->googleDocGenerationService->generate($request->course_id);

        if (isset($result['error'])) {
            return back()->with('error', $result['error']);
        }

        return back()->with('success', $result['success']);
    }
}
