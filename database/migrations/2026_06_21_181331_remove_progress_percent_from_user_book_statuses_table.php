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
        Schema::table('user_book_statuses', function (Blueprint $table) {
            $table->dropColumn('progress_percent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_book_statuses', function (Blueprint $table) {
            $table->integer('progress_percent')->default(0);
        });
    }
};
