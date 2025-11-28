<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('building_id')->constrained('buildings')->cascadeOnDelete();
            $table->string('name', 255);
            $table->string('manufacturer', 255)->nullable();
            $table->string('model', 255)->nullable();
            $table->date('installation_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};


