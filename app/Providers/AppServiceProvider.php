<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Relation::enforceMorphMap([
            'assessment' => \App\Models\CourseAssessment::class,
            'instruction' => \App\Models\CourseInstruction::class,
            'material' => \App\Models\CourseMaterial::class,
            
        ]);
    }
}
