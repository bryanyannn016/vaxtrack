<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVaccineStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vaccine_stocks', function (Blueprint $table) {
            $table->id('stock_id'); // Primary key
            $table->string('vaccine_name'); // Name of the vaccine
            $table->integer('current_quantity'); // Current stock quantity
            $table->unsignedBigInteger('last_updated_by'); // Reference to the HC Admin who updated the stock
            $table->timestamp('last_updated_at')->nullable(); // Timestamp for the last update
            $table->timestamps(); // Default created_at and updated_at columns

            // Define the foreign key constraint
            $table->foreign('last_updated_by')
                  ->references('id')->on('users')
                  ->onDelete('cascade'); // Delete stock if the user is deleted
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vaccine_stocks');
    }
}
