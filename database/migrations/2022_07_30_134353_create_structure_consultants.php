<?php

use Illuminate\Database\Eloquent\Scope;
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
        Schema::create('consultants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('doctor_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('code', 8)->index();
            $table->date('date');
            $table->time('hour');
            $table->boolean('private')->default(false);
            $table->timestamps();
        });

        Schema::create('procedures', function (Blueprint $table) {
            $table->id();
            $table->string('name', 60)->unique()->index();
            $table->string('code', 8)->index();
            $table->float('value')->default(0);
            $table->timestamps();
        });

        Schema::create('consultant_procedure', function (Blueprint $table) {
            $table->foreignId('consultant_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('procedure_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('consultant_procedure');
        Schema::dropIfExists('procedures');
        Schema::dropIfExists('consultants');
    }
};
