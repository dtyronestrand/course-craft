<?php

namespace App\Http\Controllers;

use App\Models\CourseModule;
use App\Models\ModuleObjective;
use Illuminate\Http\Request;

class CourseModuleController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'number' => 'required|integer',
            'module_objectives' => 'nullable|array',
            'course_objectives' => 'nullable|array',
            'course_assessments' => 'nullable|array',
            'course_instructions' => 'nullable|array',
            'course_materials' => 'nullable|array',
            'course_media_library_needs' => 'nullable|array',
        ]);

        $course = \App\Models\Course::findOrFail($request->course_id);
        $module = $course->modules()->create([
            'title' => $request->title,
            'order_index' => $request->number,
        ]);
        if ($request->module_objectives) {
            foreach ($request->module_objectives as $moduleObjective) {
                $module->module_objectives()->create([
                    'number' => $moduleObjective['number'],
                    'objective' => $moduleObjective['objective'],
                ]);
            }
        }
        // Attach aligned course objectives to pivot table
        if ($request->course_objectives) {
            $objectiveIds = \App\Models\CourseObjective::whereIn('number', $request->course_objectives)
                ->where('course_id', $request->course_id)
                ->pluck('id');
            $module->courseObjectives()->attach($objectiveIds);
        }

        // Create assessments
        if ($request->course_assessments) {
            foreach ($request->course_assessments as $assessment) {
                $createdAssessment = $module->assessments()->create([
                    'title' => $assessment['title'] ?? '',
                    'type' => $assessment['assesment_type'] ?? '',
                ]);
                
                if (!empty($assessment['aligned_module_objectives'])) {
                    $objectiveIds = $module->module_objectives()
                        ->whereIn('number', $assessment['aligned_module_objectives'])
                        ->pluck('id');
                    $createdAssessment->objectives()->attach($objectiveIds);
                }
            }
        }

        // Create instructional activities
        if ($request->course_instructions) {
            foreach ($request->course_instructions as $activity) {
                $createdInstruction = $module->instructions()->create([
                    'title' => $activity,
                 
                ]);
                if (!empty($activity['aligned_module_objectives'])) {
                    $objectiveIds = $module->module_objectives()
                        ->whereIn('number', $activity['aligned_module_objectives'])
                        ->pluck('id');
                    $createdInstruction->objectives()->attach($objectiveIds);
                }
            }
        }

        // Create materials
        if ($request->course_materials) {
            foreach ($request->course_materials as $material) {
               $createdMaterial = $module->materials()->create([
                    'title' => $material,
               
                ]);
                if (!empty($material['aligned_module_objectives'])) {
                    $objectiveIds = $module->module_objectives()
                        ->whereIn('number', $material['aligned_module_objectives'])
                        ->pluck('id');
                    $createdMaterial->objectives()->attach($objectiveIds);
                }
            }
        }

        // Create media/library needs
        if ($request->course_media_library_needs) {
            foreach ($request->course_media_library_needs as $need) {
               $createdNeed = $module->course_media_library_needs()->create(['name' => $need]);
               if (!empty($need['aligned_module_objectives'])) {
                   $objectiveIds = $module->module_objectives()
                       ->whereIn('number', $need['aligned_module_objectives'])
                       ->pluck('id');
                   $createdNeed->objectives()->attach($objectiveIds);
               }
            }
        }

        return redirect()->back()->with('success', 'Module created successfully');
    }

    public function update(Request $request, CourseModule $courseModule)
    {
        $request->validate([
            'course_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'number' => 'required|integer',
            'module_objectives' => 'nullable|array',
            'course_objectives' => 'nullable|array',
            'course_assessments' => 'nullable|array',
            'course_instructions' => 'nullable|array',
            'course_materials' => 'nullable|array',
            'course_media_library_needs' => 'nullable|array',
        ]);

        $courseModule->update([
            'title' => $request->title,
            'order_index' => $request->number,
        ]);

        // Sync module objectives
        $courseModule->module_objectives()->delete();
        if ($request->module_objectives) {
            foreach ($request->module_objectives as $moduleObjective) {
                $courseModule->module_objectives()->create([
                    'number' => $moduleObjective['number'],
                    'objective' => $moduleObjective['objective'],
                ]);
            }
        }

        // Sync aligned course objectives
        if ($request->course_objectives) {
            $objectiveIds = \App\Models\CourseObjective::whereIn('number', $request->course_objectives)
                ->where('course_id', $request->course_id)
                ->pluck('id');
            $courseModule->courseObjectives()->sync($objectiveIds);
        } else {
            $courseModule->courseObjectives()->detach();
        }

        // Sync assessments
        $courseModule->assessments()->delete();
        if ($request->course_assessments) {
            foreach ($request->course_assessments as $assessment) {
                $createdAssessment = $courseModule->assessments()->create([
                    'title' => $assessment['title'] ?? '',
                    'type' => $assessment['assesment_type'] ?? '',
                ]);
                
                if (!empty($assessment['aligned_module_objectives'])) {
                    $objectiveIds = $courseModule->module_objectives()
                        ->whereIn('number', $assessment['aligned_module_objectives'])
                        ->pluck('id');
                    $createdAssessment->objectives()->attach($objectiveIds);
                }
            }
        }

        // Sync instructional activities
        $courseModule->instructions()->delete();
        if ($request->course_instructions) {
            foreach ($request->course_instructions as $activity) {
                $createdInstruction = $courseModule->instructions()->create([
                    'title' => $activity,
                 
                ]);
                if (!empty($activity['aligned_module_objectives'])) {
                    $objectiveIds = $courseModule->module_objectives()
                        ->whereIn('number', $activity['aligned_module_objectives'])
                        ->pluck('id');
                    $createdInstruction->objectives()->attach($objectiveIds);
                }
            }
        }

        // Sync materials
        $courseModule->materials()->delete();
        if ($request->course_materials) {
            foreach ($request->course_materials as $material) {
               $createdMaterial = $courseModule->materials()->create([
                    'title' => $material,
               
                ]);
                if (!empty($material['aligned_module_objectives'])) {
                    $objectiveIds = $courseModule->module_objectives()
                        ->whereIn('number', $material['aligned_module_objectives'])
                        ->pluck('id');
                    $createdMaterial->objectives()->attach($objectiveIds);
                }
            }
        }

        // Sync media/library needs
        $courseModule->needs()->delete();
        if ($request->course_media_library_needs) {
            foreach ($request->course_media_library_needs as $need) {
               $createdNeed = $courseModule->course_media_library_needs()->create(['name' => $need]);
               if (!empty($need['aligned_module_objectives'])) {
                   $objectiveIds = $courseModule->module_objectives()
                       ->whereIn('number', $need['aligned_module_objectives'])
                       ->pluck('id');
                   $createdNeed->objectives()->attach($objectiveIds);
               }
            }
        }

        return redirect()->back()->with('success', 'Module updated successfully');
    }

    public function destroy(CourseModule $courseModule)
    {
        $courseId = $courseModule->course_id;
        $deletedOrderIndex = $courseModule->order_index;
        
        $courseModule->delete();
        
        // Update order_index for remaining modules
        CourseModule::where('course_id', $courseId)
            ->where('order_index', '>', $deletedOrderIndex)
            ->decrement('order_index');
        
        return redirect()->back()->with('success', 'Module deleted successfully');
    }
}