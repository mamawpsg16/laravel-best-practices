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
        Schema::table('reports', function (Blueprint $table) {
            $table->timestamp('read_at')->after('description')->nullable();
            $table->string('read_by',100)->after('read_at')->nullable();
            $table->timestamp('resolved_at')->after('read_by')->nullable();
            // $table->integer('resolved_status')->after('resolved_at')->default(0)->comment('0 - Pending, 1 - Under Investigation, 2 - Resolved');
            $table->string('resolved_by',100)->after('resolved_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn('read_at');
            $table->dropColumn('read_by');
            $table->dropColumn('resolved_at');
            $table->dropColumn('resolved_by');
        });
    }
};
