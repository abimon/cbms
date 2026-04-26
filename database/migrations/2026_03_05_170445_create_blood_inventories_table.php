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
        /*
        'din': dinController.text,
        'type': selectedType,
        'volume': volController.text.isEmpty
            ? 'Not Specified'
            : volController.text,
        'blood_type': tested ? tests[0][0] : 'Not Tested',
        'rhesus': tested ? tests[1][0] : 'Not Tested',
        'date_collected': DateTime.now().toIso8601String(),
        'location': 'Regional Trauma Center',
        'collection_agency': 'National Blood Service',
        'diseases': {
          'HIV': tested ? tests[2][0] : 'Not Tested',
          'HBV': tested ? tests[3][0] : 'Not Tested',
          'HCV': tested ? tests[4][0] : 'Not Tested',
          'Syphilis': tested ? tests[5][0] : 'Not Tested',
          'Malaria': tested ? tests[6][0] : 'Not Tested',
        },
        */
        Schema::create('blood_inventories', function (Blueprint $table) {
            $table->id();
            $table->string('din');
            $table->string('type');
            $table->string('volume');
            $table->string('blood_type');
            $table->string('rhesus')->default('NT');
            $table->string('date_collected')->default('NT');
            $table->string('location')->default('NT');
            $table->string('collection_agency');
            $table->string('HIV')->default('NT');
            $table->string('HBV')->default('NT');
            $table->string('HCV')->default('NT');
            $table->string('Syphilis')->default('NT');
            $table->string('Malaria')->default('NT');
            $table->date('release_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->enum('status', ['not_tested', 'requested', 'available', 'used', 'expired','withdrawn'])->default('available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_inventories');
    }
};
