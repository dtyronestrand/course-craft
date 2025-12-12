<?php

namespace App\Http\Controllers;

use App\Models\DevelopmentCycle;
use App\Services\DevelopmentCycleService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DevelopmentCycleController extends Controller
{
    protected $developmentCycleService;

    public function __construct(DevelopmentCycleService $developmentCycleService)
    {
        $this->developmentCycleService = $developmentCycleService;
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date'
        ]);

        $this->developmentCycleService->createDevelopmentCycle($request->all());

        return back()->with('success', 'Development Cycle created successfully.');
    }

    public function index()
    {
        $cycles = $this->developmentCycleService->getAllDevelopmentCycles();
        return response()->json($cycles);
    }
}