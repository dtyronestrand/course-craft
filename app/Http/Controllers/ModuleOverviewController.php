<?php

namespace App\Http\Controllers;

use App\Models\ModuleOverview;
use App\Services\ModuleOverviewService;
use Illuminate\Http\Request;

class ModuleOverviewController extends Controller
{
    protected $moduleOverviewService;

    public function __construct(ModuleOverviewService $moduleOverviewService)
    {
        $this->moduleOverviewService = $moduleOverviewService;
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'module' => 'required',
        ]);

        $this->moduleOverviewService->createOverview($request->all());

        return redirect()->back()->with('success', 'Module overview created successfully');
    }

    public function update(Request $request, ModuleOverview $moduleOverview)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $this->moduleOverviewService->updateOverview($moduleOverview, $request->all());

        return redirect()->back()->with('success', 'Module overview updated successfully');
    }
}
