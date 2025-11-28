<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks')->cascadeOnDelete();
            $table->foreignId('submitted_by_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('reviewed_by_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('title', 255);
            $table->text('content')->nullable();
            $table->string('status', 255)->nullable();
            $table->string('attachment_url', 512)->nullable();
            $table->timestamp('submitted_date')->nullable();
            $table->timestamp('approved_date')->nullable();
            $table->timestamps();

            $table->unique('task_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_reports');
    }
};


