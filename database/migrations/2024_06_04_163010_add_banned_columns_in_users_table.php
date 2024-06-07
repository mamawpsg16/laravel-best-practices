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
            $table->timestamp('banned_at')->after('provider_id')->nullable();
            $table->text('banned_description')->after('banned_at')->nullable();
            $table->integer('banned_days')->after('banned_description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('banned_at');
            $table->dropColumn('banned_description');
            $table->dropColumn('banned_days');
        });
    }
};
