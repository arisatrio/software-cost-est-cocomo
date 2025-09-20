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
            $table->string('programming_language')->nullable()->after('kloc');
            $table->integer('external_inputs')->default(0)->after('programming_language');
            $table->integer('external_outputs')->default(0)->after('external_inputs');
            $table->integer('external_inquiries')->default(0)->after('external_outputs');
            $table->integer('internal_files')->default(0)->after('external_inquiries');
            $table->integer('external_files')->default(0)->after('internal_files');
            $table->enum('complexity_factor', ['simple', 'average', 'complex'])->default('average')->after('external_files');
            $table->decimal('function_points', 8, 2)->default(0)->after('complexity_factor');
            $table->integer('total_sloc')->default(0)->after('function_points');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'programming_language',
                'external_inputs', 
                'external_outputs',
                'external_inquiries',
                'internal_files',
                'external_files',
                'complexity_factor',
                'function_points',
                'total_sloc'
            ]);
        });
    }
};
