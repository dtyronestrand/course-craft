<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('module_items', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('course_module_id')->constrained('course_modules')->onDelete('cascade');
            $table->morphs('itemable');

            $table->unique(['course_module_id', 'itemable_id', 'itemable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_items');
    }
};
