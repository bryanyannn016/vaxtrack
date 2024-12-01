<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVaccinationRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vaccination_records', function (Blueprint $table) {
            $table->id();  // Auto incrementing ID as the primary key
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');  // Foreign key for patients table
            $table->string('vaccine_name');  // Vaccine name (string)
            $table->string('dose_number');  // Dose number (e.g., 1 for first dose, 2 for booster)
            $table->date('administration_date'); 
            $table->string('status')->default('Pending'); // Date when the vaccine was administered
            $table->date('scheduled_date')->nullable();  // Nullable scheduled date for the next dose
            $table->unsignedBigInteger('administered_by');  // Foreign key for healthcare provider (user who administers the vaccine)
            $table->timestamps();  // Created at and updated at timestamps

            // Foreign key constraints
            $table->foreign('administered_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vaccination_records');
    }
}
