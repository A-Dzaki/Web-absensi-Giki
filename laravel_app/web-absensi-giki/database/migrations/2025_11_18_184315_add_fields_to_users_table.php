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
        if (!Schema::hasColumn('users', 'nis_nip')) {
            $table->string('nis_nip')->nullable();
        }

        if (!Schema::hasColumn('users', 'role')) {
            $table->string('role')->nullable();
        }

        if (!Schema::hasColumn('users', 'kelas')) {
            $table->string('kelas')->nullable();
        }

        // Kolom username sudah ada → JANGAN ditambah lagi
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['nis_nip', 'role', 'kelas']);
    });
    }
};
