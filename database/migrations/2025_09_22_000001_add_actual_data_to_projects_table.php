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
        Schema::table('projects', function (Blueprint $table) {
            // Actual project data
            $table->decimal('actual_effort', 8, 2)->nullable()->comment('Actual effort in person-months');
            $table->decimal('actual_schedule', 8, 2)->nullable()->comment('Actual schedule in months');
            $table->integer('actual_personnel')->nullable()->comment('Actual team size');
            $table->integer('actual_sloc')->nullable()->comment('Actual lines of code');
            
            // Project status and completion dates
            $table->enum('status', ['planning', 'in_progress', 'completed', 'cancelled'])->default('planning');
            $table->date('actual_start_date')->nullable();
            $table->date('actual_end_date')->nullable();
            
            // Accuracy metrics (calculated automatically)
            $table->decimal('effort_accuracy', 5, 2)->nullable()->comment('Effort accuracy percentage');
            $table->decimal('schedule_accuracy', 5, 2)->nullable()->comment('Schedule accuracy percentage');
            $table->decimal('personnel_accuracy', 5, 2)->nullable()->comment('Personnel accuracy percentage');
            $table->decimal('sloc_accuracy', 5, 2)->nullable()->comment('SLOC accuracy percentage');
            
            // Overall accuracy score
            $table->decimal('overall_accuracy', 5, 2)->nullable()->comment('Overall accuracy score');
            
            // Notes for post-project analysis
            $table->text('actual_notes')->nullable()->comment('Notes about actual vs estimated differences');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'actual_effort', 'actual_schedule', 'actual_personnel', 'actual_sloc',
                'status', 'actual_start_date', 'actual_end_date',
                'effort_accuracy', 'schedule_accuracy', 'personnel_accuracy', 'sloc_accuracy',
                'overall_accuracy', 'actual_notes'
            ]);
        });
    }
};