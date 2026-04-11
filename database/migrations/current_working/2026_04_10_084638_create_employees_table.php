<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('employee', function (Blueprint $table) {
            $table->id();
            $table->string('employee_code')->unique();
            $table->string('full_name');
            $table->date('dob')->nullable();
            $table->enum('gender', ['M', 'F', 'O'])->nullable();
            $table->integer('phone');
            $table->string('email');
            $table->date('joining_date')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->string('status')->nullable();
            $table->unsignedBigInteger('reporting_manager_id')->nullable();
            $table->date('probation_end_date')->nullable();
            $table->unsignedBigInteger('designation_id')->nullable();
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
        Schema::dropIfExists('employees');
    }
};
