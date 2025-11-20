<?php

namespace App\Http\Controllers;

use App\Models\ModuleWrapUp;
use App\Services\ModuleWrapUpService;
use Illuminate\Http\Request;

class ModuleWrapUpController extends Controller
{
    protected $moduleWrapUpService;

    public function __construct(ModuleWrapUpService $moduleWrapUpService)
    {
        $this->moduleWrapUpService = $moduleWrapUpService;
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'module' => 'required',
        ]);

        $this->moduleWrapUpService->createWrapUp($request->all());

        return redirect()->back()->with('success', 'Module WrapUp created successfully');
    }

    public function update(Request $request, ModuleWrapUp $moduleWrapUp)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $this->moduleWrapUpService->updateWrapUp($moduleWrapUp, $request->all());

        return redirect()->back()->with('success', 'Module WrapUp updated successfully');
    }
}
