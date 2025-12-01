<?php

namespace App\Http\Controllers;

use App\Models\AdminSetting;
use Illuminate\Http\Request;


class AdminSettingsController extends Controller 
{
    public function store(Request $request)
    {
        $request->validate([
            'capacity' => 'required|integer|min:1',
        ]);

        $adminSetting = AdminSetting::first();
        if (!$adminSetting) {
            $adminSetting = new AdminSetting();
        }
        $adminSetting->capacity = $request->input('capacity');
        $adminSetting->save();

        return back()->with('success', 'Admin settings updated successfully.');
    }

    public function getCapacity()
    {
        $adminSetting = AdminSetting::first();
        $capacity = $adminSetting ? $adminSetting->capacity : null;

        return response()->json(['capacity' => $capacity]);
    }
}