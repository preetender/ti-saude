<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('name', 60);
            $table->string('crm', 16)->unique();
            $table->string('code', 16)->index()->unique();
            $table->timestamps();
        });

        Schema::create('specialties', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->index();
            $table->string('code', 16)->index()->unique();
            $table->timestamps();
        });

        Schema::create('doctor_specialty', function (Blueprint $table) {
            $table
                ->foreignId('doctor_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table
                ->foreignId('specialty_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doctors');
        Schema::dropIfExists('specialties');
    }
};
