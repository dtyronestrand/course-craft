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
        Schema::create('course_deliverable', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('deliverable_id')->constrained()->onDelete('cascade');
            $table->date('due_date')->nullable();
            $table->boolean('is_done')->default(false);
            $table->date('date_completed')->nullable();
            $table->integer('missed_due_date_count')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_deliverable');
    }
};
