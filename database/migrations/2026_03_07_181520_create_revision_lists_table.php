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
Schema::create('revisionlists', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('topic_id');
    $table->text('content')->nullable();
    $table->timestamps();

    $table->foreign('topic_id')->references('id')->on('topics')->onDelete('cascade');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revisionlists');
    }
};
