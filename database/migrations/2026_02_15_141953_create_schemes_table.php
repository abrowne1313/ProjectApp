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
        Schema::create('schemes', function (Blueprint $table) {
            $table->id();
             $table->foreignId('Subject_id')
           ->constrained('subjects')
            ->cascadeOnDelete();
            $table->string('YearGroup');
             $table->foreignId('CreatedBy')
           ->constrained('user_data')
            ->cascadeOnDelete();            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schemes');
    }
};
            $table->id();
            $table->string('ClassName');
            $table->string('Subject');

            $table->foreignId('teacher_id')
                    ->constrained('user_data')
                    ->cascadeOnDelete();