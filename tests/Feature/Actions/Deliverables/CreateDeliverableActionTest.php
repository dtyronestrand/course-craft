<?php

namespace Tests\Feature\Actions\Deliverables;

use App\Actions\Deliverables\CreateDeliverableAction;
use App\Models\Course;
use App\Models\Deliverable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateDeliverableActionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_deliverable_and_attaches_it_to_all_courses()
    {
        // Arrange
        $course1 = Course::factory()->create();
        $course2 = Course::factory()->create();

        $action = $this->app->make(CreateDeliverableAction::class);
        $deliverableData = [
            'name' => 'New Deliverable',
            'template_days_offset' => 10,
        ];

        // Act
        $deliverable = $action->execute($deliverableData);

        // Assert
        $this->assertInstanceOf(Deliverable::class, $deliverable);
        $this->assertDatabaseHas('deliverables', [
            'id' => $deliverable->id,
            'name' => 'New Deliverable',
        ]);

        $this->assertDatabaseHas('course_deliverable', [
            'course_id' => $course1->id,
            'deliverable_id' => $deliverable->id,
        ]);
        $this->assertDatabaseHas('course_deliverable', [
            'course_id' => $course2->id,
            'deliverable_id' => $deliverable->id,
        ]);
    }
}
