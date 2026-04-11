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
        Schema::create('salary_slip', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('monthly_salary_id');
            $table->dateTime('generated_at')->default(now());
            $table->string('file_path')->nullable();
            $table->boolean('is_emailed')->default(false);
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
        Schema::dropIfExists('salary_slip');
    }
};
