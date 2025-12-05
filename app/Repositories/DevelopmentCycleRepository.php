<?php

namespace App\Repositories;

use App\Models\DevelopmentCycle;

class DevelopmentCycleRepository
{
    public function getAll()
    {
        return DevelopmentCycle::all();
    }

    public function findById($id)
    {
        return DevelopmentCycle::find($id)->get();
    }

    public function create(array $data)
    {
        return DevelopmentCycle::create($data);
    }

    public function update(DevelopmentCycle $developmentCycle, array $data)
    {
        return $developmentCycle->update($data);
    }

    public function delete(DevelopmentCycle $developmentCycle)
    {
    return $developmentCycle->delete();
    }
    public function updateOrCreate($data)
    {
        DevelopmentCycle::updateOrCreate(
            ['name' => $data['name']], 
            [
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'] ?? null,
            ]
            );
    }

}