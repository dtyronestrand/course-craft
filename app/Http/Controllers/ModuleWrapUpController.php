<?php

namespace App\Http\Controllers;

use App\Models\ModuleWrapUp;
use App\Models\ModuleItem;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ModuleWrapUpController extends Controller
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

        $moduleWrapUp = ModuleWrapUp::create([
            'content' => $request->content,
        ]);

        $maxOrderIndex = ModuleItem::where('course_module_id', $request->module)->max('order_index') ?? -1;

        ModuleItem::create([
            'course_module_id' => $request->module,
            'itemable_id' => $moduleWrapUp->id,
            'itemable_type' => 'wrap_up',
            'order_index' => $maxOrderIndex + 1,
        ]);

        return redirect()->back()->with('success', 'Module WrapUp created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(ModuleWrapUp $moduleWrapUp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ModuleWrapUp $moduleWrapUp)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ModuleWrapUp $moduleWrapUp)
    {
        $request->validate([
            'content' => 'required|string',
        ]);
    
        $moduleWrapUp->update([
            'content' => $request->content,
        ]);
    
        return redirect()->back()->with('success', 'Module WrapUp updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ModuleWrapUp $moduleWrapUp)
    {
        //
    }
}
