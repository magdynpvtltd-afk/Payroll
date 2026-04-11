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
        Schema::create('monthly_salary', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->tinyInteger('month');
            $table->smallInteger('year');
            $table->integer('working_days')->nullable();
            $table->integer('paid_days')->nullable();
            $table->decimal('gross_earning')->default(0);
            $table->decimal('total_deduction')->default(0);
            $table->decimal('net_payable');
            $table->integer('payment_status')->default(0);
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
        Schema::dropIfExists('monthly_salary');
    }
};
