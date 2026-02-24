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
        Schema::create('pupil_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('Pupil_id')
           ->constrained('pupil_data')
            ->cascadeOnDelete();
             $table->foreignId('Subject_id')
           ->constrained('subjects')
            ->cascadeOnDelete();
             $table->integer('Target');
             $table->string('YearGroup');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pupil_targets');
    }
};
