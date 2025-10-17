<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('module_objective_alignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_objective_id')->constrained('module_objectives')->onDelete('cascade');
            $table->morphs('alignable');
            $table->timestamps();
            
            $table->unique(['module_objective_id', 'alignable_id', 'alignable_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('module_objective_alignments');
    }
};