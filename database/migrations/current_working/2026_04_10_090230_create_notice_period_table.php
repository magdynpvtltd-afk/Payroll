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
        Schema::create('notice_period', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('employee_id');
        $table->integer('notice_days');
        $table->date('start_date')->nullable();
        $table->date('end_date')->nullable();
        $table->decimal('fnf_amount')->nullable();
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
        Schema::dropIfExists('notice_period');
    }
};
