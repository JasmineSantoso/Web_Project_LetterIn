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
        Schema::table('users', function (Blueprint $table) {
            // Add 'profile' column if it doesn't exist
            if (!Schema::hasColumn('users', 'profile')) {
                $table->string('profile')->nullable()->after('password');
            }
            // Add 'bio' column if it doesn't exist
            if (!Schema::hasColumn('users', 'bio')) {
                $table->text('bio')->nullable()->after('profile');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['bio', 'profile']);
        });
    }
};
