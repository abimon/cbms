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
        Schema::create('blood_storages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bloodbag_id')->constrained('blood_inventories')->cascadeOnDelete();
            $table->foreignId('bank_id')->constrained('blood_banks')->cascadeOnDelete();
            $table->string('status')->default('available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_storages');
    }
};
