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
        Schema::create('joining_letter', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('employee_id');
        $table->date('joining_date');
        $table->string('file_path');
        $table->date('issued_date')->nullable();
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
        Schema::dropIfExists('joining_letter');
    }
};
