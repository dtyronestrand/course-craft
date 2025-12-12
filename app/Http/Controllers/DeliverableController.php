<?php

namespace App\Http\Controllers;

use App\Services\DeliverableService;
use App\Models\Deliverable;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DeliverableController extends Controller
{
    protected $deliverableService;

    public function __construct(DeliverableService $deliverableService)
    {
        $this->deliverableService = $deliverableService;
    }

    public function store(Request $request)
    {
    $data = $request->validate([
        'deliverables' => 'required|array',
        'deliverables.*.id' => 'nullable|integer|exists:deliverables,id',
        'deliverables.*.name' => 'required|string|max:255',
        'deliverables.*.template_days_offset' => 'required|integer',
    ]);
  
    foreach ($data['deliverables'] as $deliverable) {
        if (empty($deliverable['id'])) {
            $this->deliverableService->createDeliverable($deliverable);
        }
    }
    return redirect()->back()->with('success', 'Deliverables created successfully');
    }

    public function update(Request $request, Deliverable $deliverable)
    {
        $data = $request->validate([
          
            'name' => 'required|string|max:255',
            'template_days_offset' => 'required|integer',
        ]);

        $this->deliverableService->updateDeliverable($deliverable, $data);
        return redirect()->back()->with('success', 'Deliverable updated successfully');
    }
 public function destroy(\App\Models\Deliverable $deliverable)
    {
        $this->deliverableService->deleteDeliverable($deliverable);
        return redirect()->back()->with('success', 'Deliverable deleted successfully');
    }
    
}