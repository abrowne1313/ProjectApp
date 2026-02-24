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
        Schema::create('user_data', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_type')
                    ->constrained('user_types')
                    ->cascadeOnDelete();
                    
            $table->String('FirstName');
            $table->String('Surname');
            $table->String('password');
            $table->String('UserEmail')->unique();
            $table->timestamps();
        });

    }

    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_data');
    }
};
