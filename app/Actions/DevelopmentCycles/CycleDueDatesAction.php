<?php

namespace App\Actions\DevelopmentCycles;



use App\Repositories\DevelopmentCycleRepository;
use App\Models\Deliverable;
class CycleDueDatesAction
{
    

    protected DevelopmentCycleRepository $developmentCycleRepository;
    public function __construct(
        DevelopmentCycleRepository $developmentCycleRepository,
    
    ) {
        $this->developmentCycleRepository = $developmentCycleRepository;
       
    }

    public function execute(int $cycleId)
    {
        $cycle = $this->developmentCycleRepository->findById($cycleId);
        if (!$cycle) {
            throw new \Exception("Development Cycle not found.");
        }
        $deliverables = Deliverable::all();
        $dueDates = [];
        foreach ($deliverables as $deliverable){
            $dueDates[] = [
                'name' => $deliverable->name,
                'due_date' => \Carbon\Carbon::parse($cycle->start_date)->addDays($deliverable->template_days_offset)
            ];
        }
        
        return $dueDates;
    }
}