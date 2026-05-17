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
    Schema::table('users', function (Blueprint $table) {
        $table->text('ai_dashboard_analysis')->nullable();
        $table->timestamp('ai_analysis_cached_at')->nullable(); // Tracks when it was generated
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['ai_dashboard_analysis', 'ai_analysis_cached_at']);
    });
}
};
