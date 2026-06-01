<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::table('tasks', function (Blueprint $table) {
        // Add the missing time estimate column first (e.g., after the 'id' or 'title' or at the end)
        $table->string('ai_time_estimate')->nullable(); 
        
        // Add the plan column (if you haven't added it anywhere else yet)
        $table->text('ai_study_plan')->nullable();
        
        // Add the tips column right after the plan
        $table->text('ai_study_tips')->nullable()->after('ai_study_plan');
    });
}

public function down(): void
{
    Schema::table('tasks', function (Blueprint $table) {
        // Always clean up all columns if rolled back
        $table->dropColumn(['ai_time_estimate', 'ai_study_plan', 'ai_study_tips']);
    });
}
};