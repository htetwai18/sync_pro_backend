<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('task_parts', function (Blueprint $table) {
            $table->foreignId('task_id')->constrained('tasks')->cascadeOnDelete();
            $table->foreignId('part_id')->constrained('parts')->cascadeOnDelete();
            $table->integer('quantity_used')->default(0);
            $table->timestamps();

            $table->primary(['task_id', 'part_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_parts');
    }
};


