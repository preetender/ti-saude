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
            $table->string('code', 8)->index()->unique();
            $table->string('name', 60);
            $table->string('crm', 16)->unique();
            $table->timestamps();
        });

        Schema::create('specialities', function (Blueprint $table) {
            $table->id();
            $table->string('code', 8)->index()->unique();
            $table->string('name', 100)->index();
            $table->timestamps();
        });

        Schema::create('doctor_speciality', function (Blueprint $table) {
            $table
                ->foreignId('doctor_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table
                ->foreignId('speciality_id')
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
        Schema::dropIfExists('doctor_specialty');
        Schema::dropIfExists('doctors');
        Schema::dropIfExists('specialties');
    }
};
