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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('code', 8)->unique()->index();
            $table->string('name', 60);
            $table->date('birth_date');
            $table->timestamps();
        });

        Schema::create('patient_phones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('number', 11);
            $table->timestamps();
        });

        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('code', 8);
            $table->string('phone', 11);
            $table->tinyText('description')->nullable();
            $table->timestamps();
        });

        Schema::create('patient_plan', function (Blueprint $table) {
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('plan_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('contract_number', 6);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patient_phones');
        Schema::dropIfExists('patient_plan');
        Schema::dropIfExists('plans');
        Schema::dropIfExists('patients');
    }
};
