<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Course;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('course_objectives', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Course::class)->constrained()->onDelete('cascade');
            $table->string('number');
            $table->text('objective');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_objectives');
    }
};
