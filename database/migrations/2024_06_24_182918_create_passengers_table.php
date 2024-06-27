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
        Schema::create('passengers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('scooter_id');
            $table->string('email')->unique()->nullable();
            $table->string('name');
            $table->string('duration');
            $table->dateTime('start');
            $table->dateTime('end');
            $table->timestamps();

            $table->foreign('scooter_id')->references('id')->on('scooters')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('passengers');
    }
};
