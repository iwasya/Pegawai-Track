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
        Schema::table('akun_user', function (Blueprint $table) {
            $table->timestamp('last_seen')->nullable()->after('updated_at');
            $table->boolean('is_online')->default(false)->after('last_seen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('akun_user', function (Blueprint $table) {
            $table->dropColumn('last_seen');
            $table->dropColumn('is_online');
        });
    }
};
