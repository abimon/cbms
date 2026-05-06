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
        Schema::create('relay_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('board_id');
            $table->string('relay_id');
            $table->string('status');
            $table->boolean('isDone')->default(false);
            $table->string('code');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('relay_statuses');
    }
};
