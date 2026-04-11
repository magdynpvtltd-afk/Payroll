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
        Schema::create('employees_domain', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('employee_id');
        $table->integer('skill')->nullable();
        $table->string('domain')->nullable();
        $table->enum('proficiency_level', ['beginner', 'intermediate', 'expert'])->nullable();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees_domain');
    }
};
