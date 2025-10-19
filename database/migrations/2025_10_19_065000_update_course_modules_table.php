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
        Schema::table('course_modules', function (Blueprint $table) {
            $table->text('module_overview')->nullable()->after('module_objectives');
            $table->text('module_wrap_up')->nullable()->after('module_overview');
            $table->foreignId('module_items_id')->nullable()->constrained('module_items')->onDelete('set null')->after('module_wrap_up');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
