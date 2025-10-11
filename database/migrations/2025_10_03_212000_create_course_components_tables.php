<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Course;
use App\Models\CourseObjective;
return new class extends Migration
{
    public function up(): void
    {
        // Core module structure
        Schema::create('course_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Course::class)->constrained()->onDelete('cascade');
            $table->string('title');
            $table->foreignIdFor(CourseObjective::class)->nullable()->constrained()->onDelete('set null');
            $table->jsonb('module_objectives')->nullable();
            $table->integer('order_index');
            $table->timestamps();
        });

        // Assessments linked to modules
        Schema::create('course_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->nullable()->constrained('course_modules')->onDelete('cascade');
            $table->string('title');
            $table->string('type');
            $table->jsonb('aligned_module_objectives');
            $table->text('purpose')->nullable();
            $table->text('criteria')->nullable();
            $table->integer('point_value')->nullable();
            $table->string('time_limit')->nullable();
            $table->timestamps();
        });

        // Activities linked to modules
        Schema::create('course_instruction', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->nullable()->constrained('course_modules')->onDelete('cascade');
            $table->string('title');
            $table->string('type');
            $table->text('content')->nullable();
            $table->timestamps();
        });

        // Materials linked to modules
        Schema::create('course_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Course::class)->constrained()->onDelete('cascade');
            $table->foreignId('module_id')->nullable()->constrained('course_modules')->onDelete('cascade');
            $table->string('title');
            $table->string('type');
            $table->string('url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_materials');
        Schema::dropIfExists('course_activities');
        Schema::dropIfExists('course_assessments');
        Schema::dropIfExists('course_modules');
    }
};