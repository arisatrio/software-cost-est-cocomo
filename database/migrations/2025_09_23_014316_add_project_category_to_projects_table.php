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
            $table->enum('project_category', ['organic', 'semi_detached', 'embedded'])
                  ->default('organic')
                  ->after('complexity_factor')
                  ->comment('COCOMO II Project Category: organic, semi_detached, embedded');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('project_category');
        });
    }
};
