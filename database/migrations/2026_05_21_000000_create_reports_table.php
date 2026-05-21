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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reporter_id');
            $table->unsignedBigInteger('reported_id');
            $table->unsignedBigInteger('review_id')->nullable();
            $table->string('category');
            $table->text('content')->nullable();
            $table->text('reported_review_text')->nullable();
            $table->integer('reported_review_rating')->nullable();
            $table->string('status')->default('pending'); // pending, resolved, rejected
            $table->timestamps();

            // Foreign Key Constraints
            $table->foreign('reporter_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('reported_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('review_id')->references('id')->on('reviews')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
