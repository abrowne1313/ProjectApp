<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pupil_data', function (Blueprint $table) {
            $table->id();
            $table->string('FirstName');
            $table->string('Surname');
            $table->date('DateOfBirth');
            $table->string('Gender');
            $table->string('FormClass');
            $table->string('SEN')->nullable();
            $table->string('Medical')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pupil_datas');
    }
};
