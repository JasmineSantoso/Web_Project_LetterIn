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
            $table->foreignId('reporter_id')->constrained('users', 'user_id')->cascadeOnDelete();
            $table->foreignId('reported_id')->constrained('users', 'user_id')->cascadeOnDelete();
            $table->foreignId('review_id')->nullable()->constrained('reviews', 'id')->nullOnDelete();
            $table->string('category');
            $table->text('content')->nullable();
            $table->text('reported_review_text')->nullable();
            $table->integer('reported_review_rating')->nullable();
            $table->string('status')->default('pending'); // pending, resolved, rejected
            $table->timestamps();

            // Foreign Key Constraints

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
