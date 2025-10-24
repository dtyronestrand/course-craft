<?php

namespace App\Http\Controllers;

use App\Models\ModuleOverview;
use App\Models\ModuleItem;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ModuleOverviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
  
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
            'content' => 'required|string',
            'module' => 'required',
        ]);

        $moduleOverview = ModuleOverview::create([
            'content' => $request->content,
        ]);

        ModuleItem::create([
            'course_module_id' => $request->module,
            'itemable_id' => $moduleOverview->id,
            'itemable_type' => ModuleOverview::class,
        ]);

        return redirect()->back()->with('success', 'Module overview created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(ModuleOverview $moduleOverview)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ModuleOverview $moduleOverview)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ModuleOverview $moduleOverview)
    {
        $request->validate([
            'content' => 'required|string',
        ]);
    
        $moduleOverview->update([
            'content' => $request->content,
        ]);
    
        return redirect()->back()->with('success', 'Module overview updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ModuleOverview $moduleOverview)
    {
        //
    }
}
