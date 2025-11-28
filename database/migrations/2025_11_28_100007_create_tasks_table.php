<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('asset_id')->constrained('assets')->cascadeOnDelete();
            $table->foreignId('created_by_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('assigned_to_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->enum('status', ['pending','assigned','in_progress','completed','rejected'])->default('pending');
            $table->enum('priority', ['low','medium','high','urgent'])->default('medium');
            $table->string('type', 100);
            $table->text('notes')->nullable();
            $table->date('preferred_date')->nullable();
            $table->string('preferred_time_slot', 50)->nullable();
            $table->text('scheduling_details')->nullable();
            $table->text('special_instructions')->nullable();
            $table->timestamp('request_date')->nullable();
            $table->timestamp('assigned_date')->nullable();
            $table->timestamp('completed_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};


