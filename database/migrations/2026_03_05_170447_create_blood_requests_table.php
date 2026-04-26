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
        Schema::create('blood_requests', function (Blueprint $table) {
            $table->id();
            $table->enum('request_type', ['component', 'whole_blood'])->default('whole_blood');
            $table->enum('blood_type',['A-', 'A+', 'B-', 'B+', 'AB-', 'AB+', 'O-', 'O+']);
            $table->integer('quantity');
            $table->string('hospital');
            $table->string('contact_phone');
            $table->text('reason');
            $table->enum('status', ['pending', 'approved', 'fulfilled', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_requests');
    }
};
