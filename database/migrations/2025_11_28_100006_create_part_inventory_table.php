<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('part_inventory', function (Blueprint $table) {
            $table->foreignId('part_id')->constrained('parts')->cascadeOnDelete();
            $table->foreignId('location_id')->constrained('inventory_locations')->cascadeOnDelete();
            $table->integer('quantity_on_hand')->default(0);
            $table->timestamps();

            $table->primary(['part_id', 'location_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('part_inventory');
    }
};


