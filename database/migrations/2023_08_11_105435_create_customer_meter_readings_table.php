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
        Schema::create('customer_meter_readings', function (Blueprint $table) {
            $table->id();
            $table->string('account_number');
            $table->string('reading_date');
            $table->integer('reading_value');
            $table->integer('fixed_charge');
            $table->integer('first_range_amount');
            $table->integer('second_range_amount');
            $table->integer('third_range_amount');
            $table->integer('total_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_meter_readings');
    }
};
