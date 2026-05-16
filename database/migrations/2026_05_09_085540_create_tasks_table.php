<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void {
    Schema::create('tasks', function (Blueprint $table) {
        $table->id();
        // This links the task to a specific user
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
        $table->string('title');
        $table->text('description')->nullable();
        $table->enum('priority', ['Low', 'Medium', 'High'])->default('Medium');
        $table->enum('status', ['Pending', 'Completed'])->default('Pending');
        $table->dateTime('due_date');
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};