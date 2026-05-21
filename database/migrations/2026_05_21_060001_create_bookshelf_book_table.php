<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookshelf_book', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bookshelf_id');
            $table->unsignedBigInteger('book_id');
            $table->timestamps();

            $table->foreign('bookshelf_id')->references('id')->on('bookshelves')->onDelete('cascade');
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');

            $table->unique(['bookshelf_id', 'book_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookshelf_book');
    }
};
